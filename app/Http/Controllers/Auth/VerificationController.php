<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Email Verification Controller
  |--------------------------------------------------------------------------
  |
  | This controller is responsible for handling email verification for any
  | user that recently registered with the application. Emails may also
  | be re-sent if the user didn't receive the original email message.
  |
  */

  use VerifiesEmails;

  /**
   * Where to redirect users after verification.
   *
   * @var string
   */
  protected $redirectTo = '/';

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
//    $this->middleware('auth');
    $this->middleware('signed')->only('verify');
    $this->middleware('throttle:6,1')->only('verify', 'resend');
  }

  /**
   * Verify account without login
   *
   * @param Request $request
   * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   */
  public function verify(Request $request)
  {
    $user = User::findOrfail($request->route('id'));
    if (isset($user->_redirect) && $user->_redirect  != '') {
      $this->redirectTo = $user->_redirect.'?verify=verified';
    }

    if ($user->markEmailAsVerified()) {
      event(new Verified($request->user()));
    }

    return redirect($this->redirectTo)->with('verified', true);
  }
}
