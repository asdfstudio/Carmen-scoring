<div class="collapse navbar-collapse" id="app-navbar-collapse">
  <!-- Left Side Of Navbar -->
  <ul class="nav navbar-nav">
      <li><a href="{{ url('/admin/organization') }}">Organizations</a></li>
      <li><a href="{{ url('/admin/choir') }}">Choirs</a></li>
      <li><a href="{{ url('/admin/school') }}">Schools</a></li>
      <li><a href="{{ url('/admin/competition') }}">Competitions</a></li>
      <li><a href="{{ url('/admin/judge') }}">Judges</a></li>
  </ul>

  <!-- Right Side Of Navbar -->
  <ul class="nav navbar-nav navbar-right">
      <!-- Authentication Links -->
      @if (Auth::guest())
          <li><a href="{{ url('/login') }}">Login</a></li>
          <li><a href="{{ url('/register') }}">Register</a></li>
      @else
          <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                  {{ Auth::user()->email }} <span class="caret"></span>
              </a>

              <ul class="dropdown-menu" role="menu">
                  <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
              </ul>
          </li>
      @endif
  </ul>
</div>