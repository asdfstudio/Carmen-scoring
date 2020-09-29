@extends('layouts.app')

<!-- Main Content -->
@section('body-content')

  <div class="login-container extra-width">
    <div class="logo-container">
      <img src="/images/logo-2020.png" alt="Carmen Scoring System"  />
    </div>

    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif

    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
      {{ csrf_field() }}

      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="col-md-3 control-label">E-Mail Address</label>

        <div class="col-md-8">
          <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

          @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
          @endif
        </div>
      </div>

      <div class="form-group">
        <div class="col-md-8 col-md-offset-3 flex justify-content-between">
          <button type="submit" class="btn btn-primary">
            Send Password Reset Link
          </button>

          <a class="btn btn-link" href="{{ url('/login') }}">Go to Login</a>
        </div>
      </div>
    </form>

  </div>
@endsection
