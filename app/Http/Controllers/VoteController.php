<?php

namespace App\Http\Controllers;

use App\Audience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Vote;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function vote(Request $request)
  {
    $audientId = $request->input('audientId');
    $audience = Audience::find($audientId);

    if ($audience->is_required_login && !Auth::check()) return $this->requireLogin();

    if (NULL === $audience) return $this->notOpen();

    if ($audience->disable_vote) return $this->disableVote();

    $user = Auth::user();

    if ($user->email_verified_at === "0000-00-00 00:00:00"){ return $this->requireActiveAccount();}

    return $this->addVote([
      'user' => $user,
      'vote_id' => $request->input('voteId'),
      'audience' => $audience
    ]);
  }

  /**
   * Message to active account
   *
   * @return JsonResponse
   */
  public function requireActiveAccount()
  {
    return response()->json([
      'message' => 'Please active your account to vote'
    ], 500);
  }

  /**
   * Check user Voted for this campaign or not
   *
   * @param $user
   * @param $audienceId
   * @return bool
   */
  public function checkUserVoted($audienceId, $user)
  {
    if (NULL === $user->voted) {
      return false;
    }
    return in_array($audienceId, $user->voted);
  }

  /**
   * Update voted id to User data
   *
   * @param $user
   * @param $audienceId
   * @return void
   */
  public function updateUserVoted($user, $audienceId)
  {
    $userVoted = (NULL === $user->voted) ? [] : $user->voted;
    if (count($userVoted)) {
      array_push($userVoted, $audienceId);
    }
    if (0 === count($userVoted)) {
      $userVoted = [$audienceId];
    }

    $user->voted = $userVoted;
    $user->save();
  }

  /**
   * @param $user
   * @param $audienceId
   */
  public function removeVotedFromUser($user, $audienceId)
  {
    $userVoted = (NULL === $user->voted) ? [] : $user->voted;
    if (count($userVoted)) {
      $userVoted = array_diff($userVoted, [$audienceId]);
    }
    $user->voted = $userVoted;
    $user->save();
  }

  /**
   * Add vote
   *
   * @param $data
   * @return JsonResponse
   */
  public function addVote($data)
  {
    $message = 'Thanks for your voting! If you made a mistake, you can undo your action';
    $audience = $data['audience'];
    $vote = Vote::where('vote_id', $data['vote_id'])->where('audience_id', $audience->id)->first();
    $newVote = $data['user']->id . '_' . $audience->id . '_' . $data['vote_id'];
    $votes = (NULL == $vote) ? [] : (array)$vote->votes;
    $data['votes'] = $votes;
    $data['newVote'] = $newVote;

    if (NULL === $vote) {
      $vote = $this->firstVote($data);
    }

    if ($audience->is_premium_vote) {
      $message = 'Thanks for your voting!';
      $data['votes'] = $vote->premium_votes?$vote->premium_votes:[];
      return $this->premiumVote($data, $vote, $message);
    }

    if (in_array($newVote, $votes)) {
      $votes = array_diff($votes, [$newVote]);
      return $this->cancelVote($data, $vote, $votes);
    }

    return $this->freeVote($data, $vote, $message);
  }

  /**
   * @param $data
   * @param $vote
   * @param $message
   * @return JsonResponse
   */
  public function premiumVote($data, $vote, $message)
  {
    $user = $data['user'];
    if ($user->petl_point > 0) {
      $votes = $data['votes'];
      array_push($votes, $data['newVote']);
      $this->updateUserVoted($data['user'], $data['audience']->id);  //Update voted id to user data
      $this->updateUserPetlPoint($data['user']); //Update user petl points
      return $this->updatePremiumVote($vote, $votes, $message);
    }

    return response()->json(['message' => 'not_enough_petl_points'], 500);
  }

  /**
   * @param $user
   * @return int
   */
  public function updateUserPetlPoint($user)
  {
    $user->petl_point = $user->petl_point - 1;
    $user->save();
    return $user->petl_point;
  }

  /**
   * @param $data
   * @param $vote
   * @param $message
   * @return JsonResponse
   */
  public function freeVote($data, $vote, $message)
  {
    $votes = $data['votes'];
    if ($this->checkUserVoted($data['audience']->id, $data['user'])) {
      return response()->json([
        'message' => 'No more votes available for your account'
      ], 500);
    }

    array_push($votes, $data['newVote']);
    $this->updateUserVoted($data['user'], $data['audience']->id);  //Update voted id to user data
    return $this->updateVote($vote, $votes, $message);
  }

  /**
   * @param $data
   * @param $vote
   * @param $votes
   * @return JsonResponse
   */
  public function cancelVote($data, $vote, $votes)
  {
    $message = 'Vote successfully canceled!';
    $this->removeVotedFromUser($data['user'], $data['audience']->id);
    return $this->updateVote($vote, $votes, $message);
  }

  /**
   * Add first Vote
   *
   * @param $data
   * @param $message
   * @return Vote
   */
  public function firstVote($data)
  {
    $vote = new Vote();
    $vote['audience_id'] = $data['audience']->id;
    $vote['vote_id'] = $data['vote_id'];
    $vote->save();
    return $vote;
  }

  /**
   * @param $vote
   * @param $votes
   * @param $message
   * @return JsonResponse
   */
  public function updatePremiumVote($vote, $votes, $message)
  {
    $freeVote = $vote->votes ? $vote->votes : [];
    $freeVoteCount = count($freeVote);
    $vote['premium_votes'] = $votes;
    $vote['vote_count'] = count($votes) + $freeVoteCount;
    $vote->save();

    return response()->json([
      'message' => $message,
      'vote_count' => $vote['vote_count'],
      'petl_point' => Auth::user()->petl_point
    ]);
  }

  /**
   * @param $vote
   * @param $votes
   * @param $message
   * @return JsonResponse
   */
  public function updateVote($vote, $votes, $message)
  {
    $premiumVote = $vote->premium_votes ? $vote->premium_votes : [];
    $premiumVoteCount = count($premiumVote);
    $vote['votes'] = array_unique($votes);
    $vote['vote_count'] = count(array_unique($votes)) + $premiumVoteCount;
    $vote->save();

    return response()->json([
      'message' => $message,
      'vote_count' => $vote['vote_count']
    ]);
  }

  /**
   * Still not setting vote page
   *
   * @return JsonResponse
   */
  public function notOpen()
  {
    return response()->json([
      'message' => 'Vote still not open please contact the administrator'
    ], 500);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function requireLogin()
  {
    return response()->json(['message' => 'need_login'], 500);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function disableVote()
  {
    return response()->json(['message' => 'Vote has been disabled!'], 500);
  }
}
