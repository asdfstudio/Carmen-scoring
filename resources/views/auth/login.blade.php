@extends('layouts.app')

@section('title')
  Login | @parent
@endsection

@section('body-content')
<div class="login-container">
    <div class="logo-container">
      <img src="/images/logo-with-text.png" width="185" alt="Carmen Scoring System"  />
    </div>

    <form role="form" method="POST" action="{{ url('/login') }}">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label for="username" class=" control-label">Username or Email Address</label>

                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}">

                @if ($errors->has('username'))
                    <span class="help-block">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                @endif

        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label for="password" class=" control-label">Password</label>

                <input id="password" type="password" class="form-control" name="password">

                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
        </div>

        <div class="form-group">
            <div class="">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="">
                <button type="submit" class="btn btn-primary">
                    Login
                </button>

                <!--<a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>-->
            </div>
        </div>
    </form>
  </div>
@endsection
