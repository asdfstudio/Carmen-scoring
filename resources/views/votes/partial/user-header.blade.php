<div id="myDIV" class="login-navigation login @if (!Auth::guest()) loggedInSuccessfully @endif">
  @if (Auth::guest())
    <div class="not-login">
      <button class="btn active" data-toggle="modal" data-target="#loginModal">
        <i class="fas fa-user"></i> Login
      </button>
      <button class="btn btn-register" data-toggle="modal" data-target="#SignupModal">Sign up</button>
    </div>
  @else
    <div class="userloggedin">
        <span class="auth-email">{{Auth::user()->email}}</span>
        <span class="auth-petlpoint"><span id="petlpoints">{{ number_format(Auth::user()->petl_point)}}</span> Petl Points</span>
        <button class="btn active auth-petlpoint"  data-toggle="modal" data-target="#payment-modal">
          Buy Petl Points
        </button>
      <button class="btn active" onclick="document.getElementById('logout-form').submit();">
        <i class="fas fa-user"></i> Logout
      </button>
    </div>
  @endif
  <form id="logout-form" action="/vote-logout" method="POST" class="d-none">
    @csrf
  </form>
</div>
