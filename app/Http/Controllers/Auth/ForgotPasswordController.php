<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Password Reset Controller
  |--------------------------------------------------------------------------
  |
  | This controller is responsible for handling password reset emails and
  | includes a trait which assists in sending these notifications from
  | your application to your users. Feel free to explore this trait.
  |
  */

  use SendsPasswordResetEmails;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest');
  }

  /**
   * Send a reset link to the given user.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  public function sendResetLinkEmailAjax(Request $request)
  {
    $this->validateEmail($request);

    $response = $this->broker()->sendResetLink(
      $request->only('email')
    );

    return $response == Password::RESET_LINK_SENT
      ? $this->sendResetLinkResponseAjax($request, $response)
      : $this->sendResetLinkFailedResponseAjax($request, $response);
  }

  /**
   * Get the response for a successful password reset link.
   *
   * @param \Illuminate\Http\Request $request
   * @param string $response
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  protected function sendResetLinkResponseAjax(Request $request, $response)
  {
    $user = User::where('email', $request->input('email'))->first();
    $user->_redirect = $request->input('redirect');
    $user->save();
    return response()->json(['message' => 'Successfully reset your password']);
  }

  /**
   * Get the response for a failed password reset link.
   *
   * @param \Illuminate\Http\Request $request
   * @param string $response
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  protected function sendResetLinkFailedResponseAjax(Request $request, $response)
  {
    return response()->json(['errors' => 'Error have no email in our system!']);
  }
}
