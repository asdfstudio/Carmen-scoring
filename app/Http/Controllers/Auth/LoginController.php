<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */
  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = '/';

  protected $username = 'username';


  public function username()
  {
    return 'username';
  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest', ['except' => 'logout']);
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param array $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'name' => 'required|max:255',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|min:6|confirmed',
    ]);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param array $data
   * @return User
   */
  protected function create(array $data)
  {
    return User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => bcrypt($data['password']),
    ]);
  }

  protected function authenticated($request, $user)
  {
    if ($user->isAdmin()) {
      return redirect()->intended(route('admin.organization.index'));
    } elseif ($user->isOrganizer()) {
      return redirect()->intended(route('organizer.competition.index'));
    } elseif ($user->isJudge()) {
      return redirect()->intended(route('judge.competition.index'));
    }

    return redirect()->intended();
  }

  public function loginAjax(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'password' => 'required',
    ]);

    if ($validator->passes()) {
      if (auth()->attempt(array('email' => $request->input('username'),
        'password' => $request->input('password')), true)) {
        return view('votes.partial.user-header' );
      }
      return response()->json(['error' => 'Sorry User not found.'], 500);
    }

    return response()->json(['error' => $validator->errors()->all()]);
  }

  /**
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function voteLogOut(Request $request)
  {
    $this->guard()->logout();

    $request->session()->invalidate();

    return redirect()->back();
  }

  // Override AuthenticatesUsers method to allow logging in via username or email address
  protected function credentials(Request $request)
  {

    $field = filter_var($request->input($this->username()), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $request->merge([$field => $request->input($this->username())]);
    return $request->only($field, 'password');
  }
}
