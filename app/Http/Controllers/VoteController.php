<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Audience;
use App\Vote;

class VoteController extends Controller
{
    private $successful_message;

    public function __construct()
    {
        $this->successful_message = 'Thank you for your vote! If you made a mistake, click on the participant you voted for to undo it.';
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function vote(Request $request)
    {
        $user = Auth::user();

        if (!$user){
            return $this->requireLogin();
        }

        $audienceId = $request->input('audienceId');
        $audience = Audience::find($audienceId);

        if (NULL === $audience) {
            return $this->notOpen();
        } elseif ($audience->disable_vote) {
            return $this->disableVote();
        } elseif ($user->email_verified_at === "0000-00-00 00:00:00") {
            return $this->requireActiveAccount();
        } elseif ($audience->audienceable->is_completed){
            return response()->json(['message' => 'This contest has been already archived.'], 500);
        }

        return $this->addVote(['vote_id' => $request->input('voteId'),'audience' => $audience]);
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
        if (NULL === $user->voted)  return false;
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
        $message = $this->successful_message;
        $audience = $data['audience'];
        $user = Auth::user();

        $vote = Vote::where('vote_id', $data['vote_id'])->where('audience_id', $audience->id)->first();

        if (!$user ) {
            $newVote =  $_SERVER['REMOTE_ADDR'] . '_' . $audience->id . '_' . $data['vote_id'];
        } else {
            $newVote = $user->id . '_' . $audience->id . '_' . $data['vote_id'];
        }

        $votes = (NULL == $vote) ? [] : (array)$vote->votes;

        $data['votes'] = $votes;
        $data['newVote'] = $newVote;

        if (NULL === $vote) {  $vote = $this->firstVote($data);  }

        if ($audience->is_premium_vote) {
            $message = 'Thanks for voting!';
            $data['votes'] = $vote->premium_votes?$vote->premium_votes:[];
            return $this->premiumVote($data, $vote, $message);
        }

        if (in_array($newVote, $votes)) {
            $votes = array_diff($votes, [$newVote]);
            return $this->cancelVote($data, $vote, $votes);
        }

        if (!Auth::user()) {
            return $this->anonymousVote($data, $vote, $message);
        }

        return $this->freeVote($data, $vote, $message);
    }

    /**
     * @param $data
     * @param $vote
     * @param $message
     * @return JsonResponse
     */
    public function anonymousVote($data, $vote, $message) {
        $votes = $data['votes'];
        $newVote = $data['newVote'];
        $cookieName = 'audience_voted_'.$data['audience']->id.'';

        if (Cookie::get($cookieName) != null) {
            return response()->json([
                'message' => 'No more votes available for your account'
            ], 500);
        }
        $voteId = $data['newVote'];
        array_push($votes, $newVote);
        Cookie::queue($cookieName, $data['audience']->id, 512640);
        return $this->updateVote($vote, $votes, $message, $voteId);
    }

    /**
     * @param $data
     * @param $vote
     * @param $message
     * @return JsonResponse
     */
    public function premiumVote($data, $vote, $message)
    {
        $user = Auth::user();
        if ($user->petl_point > 0) {
            $votes = $data['votes'];
            $voteId = $data['newVote'];
            array_push($votes, $data['newVote']);
            $this->updateUserVoted($user, $data['audience']->id);  //Update voted id to user data
            $this->updateUserPetlPoint($user); //Update user petl points
            return $this->updatePremiumVote($vote, $votes, $message, $voteId);
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
        $user = Auth::user();
        if ($this->checkUserVoted($data['audience']->id, $user)) {
            return response()->json([
                'message' => 'No more votes available for your account'
            ], 500);
        }
        $voteId = $data['newVote'];
        array_push($votes, $data['newVote']);
        $this->updateUserVoted($user, $data['audience']->id);  //Update voted id to user data
        return $this->updateVote($vote, $votes, $message, $voteId);
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

        $user = Auth::user();
        if ($user) {
            $this->removeVotedFromUser($user, $data['audience']->id);
        }
        $voteId = $data['newVote'];
        Cookie::queue(Cookie::forget('audience_voted_'.$data['audience']->id));
        return $this->updateVote($vote, $votes, $message, $voteId);
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
    public function updatePremiumVote($vote, $votes, $message, $voteId)
    {

        $freeVote = $vote->votes ? $vote->votes : [];
        $freeVoteCount = count($freeVote);
        $vote['premium_votes'] = $votes;
        $vote['vote_count'] = count($votes) + $freeVoteCount;
        $vote->save();

        // get vote numbers for login user
        $freeVote = isset($vote->votes) ? json_encode($vote->votes) : "";
        $freeVoteCount = substr_count($freeVote, $voteId);
        $vote_count = substr_count(json_encode($votes), $voteId) + $freeVoteCount;

        return response()->json([
            'message' => $message,
            'vote_count' => $vote_count,
            'petl_point' => Auth::user()->petl_point
        ]);
    }

    /**
     * @param $vote
     * @param $votes
     * @param $message
     * @return JsonResponse
     */
    public function updateVote($vote, $votes, $message, $voteId)
    {
        $premiumVote = $vote->premium_votes ? $vote->premium_votes : [];
        $premiumVoteCount = count($premiumVote);
        $vote['votes'] = array_unique($votes);
        $vote['vote_count'] = count(array_unique($votes)) + $premiumVoteCount;
        $vote->save();

        // get vote numbers for login user
        $premiumVoteStr = isset($vote->premium_votes) ? json_encode($vote->premium_votes) : "";
        $premiumVoteCount = substr_count($premiumVoteStr, $voteId);
        $vote_count = substr_count(json_encode(array_unique($votes)), $voteId) + $premiumVoteCount;

        return response()->json([
            'message' => $message,
            'vote_count' => $vote_count
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
