<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cartalyst\Stripe\Laravel\Facades\Stripe;

class PaymentController extends Controller
{

  /**
   * @param $request
   * @return mixed
   */
  public function validateData(Request $request)
  {
    return $request->validate([
      'petl_point' => 'required',
      'card_no' => 'required',
      'ccExpiryMonth' => 'required',
      'ccExpiryYear' => 'required',
      'cvvNumber' => 'required',
    ]);
  }

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function paymentStripe(Request $request)
  {
    $this->validateData($request);
    $input = $request->all();
    unset($input['_token']);
    $amount = $input['petl_point'];

    try {
      $token = $this->createToken($input);
      if (!isset($token['id'])) {
        return response()->json(['message' => 'Something went wrong. Please refresh this page and try again.'], 500);
      }
      return $this->charge($token, $amount);
    } catch (Exception $e) {
      return $this->throwErrorMessage($e->getMessage());
    } catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
      return $this->throwErrorMessage($e->getMessage());
    } catch (MissingParameterException $e) {
      return $this->throwErrorMessage($e->getMessage());
    }
  }

  /**
   * @param $token
   * @param $amount
   * @return mixed
   */
  public function charge($token, $amount )
  {
    $charge = Stripe::charges()->create([
      'card' => $token['id'],
      'currency' => 'USD',
      'amount' => $amount,
      'description' => 'Buy Petl Points',
    ]);

    if ($charge['status'] == 'succeeded') {
      $userUpdatedPoint = $this->updateUserPetlPoints($amount); //Update point to user
      return response()->json([
        'message' => "Successfully paid for $amount Petl Points!",
        'data' => $charge,
        'petl_points' => number_format($userUpdatedPoint->petl_point)
      ]);
    }

    return $this->throwErrorMessage("Couldn't add Petl Points to wallet.");
  }

  /**
   * @param $data
   * @return mixed
   */
  public function createToken($data)
  {
    return Stripe::tokens()->create([
      'card' => [
        'number' => $data['card_no'],
        'exp_month' => $data['ccExpiryMonth'],
        'exp_year' => $data['ccExpiryYear'],
        'cvc' => $data['cvvNumber'],
      ],
    ]);
  }

  /**
   * @param $message
   * @return JsonResponse
   */
  public function throwErrorMessage($message)
  {
    return response()->json(['message' => $message], 500);
  }

  /**
   * @param $amount
   * @return Authenticatable|null
   */
  public function updateUserPetlPoints($amount)
  {
    $currentUser = Auth::user();
    $oldPetlPoints = intval($currentUser->petl_point);
    $currentUser->petl_point = $oldPetlPoints + $amount;
    $currentUser->save();
    return $currentUser;
  }
}
