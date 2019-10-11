<div class="header-wrap judge">
  <div class="header body-width">
    <ul class="navigation">
      <!--<li class="title">Judge</li>-->

      <li class="title">{{ Auth::user()->display_name }}</li>

      <li>
        @php $link_class = Request::segment(1) == 'judge' AND Request::segment(2) == 'competitions' ? 'active' : false; @endphp
        <a href="{{ route('judge.competition.index') }}" class="{{ $link_class }}">My Competitions</a>
      </li>

    </ul>

    @if(Auth::user()->isOrganizer() == false)
      <ul class="user-actions navigation">
        <li>
          <a href="{{ route('profile.edit') }}">My Profile</a>
        </li>
        <li>
          <a href="{{ url('logout') }}">Logout</a>
        </li>
      </ul>
    @endif
  </div>
</div>
