<div class="header-wrap organizer">
  <div class="header body-width">
    <ul class="navigation">
      @if (Auth::user()->isAdmin())
        <li class="title">
          <span class="intro">Acting as:</span>
          <span class="organization-name">{{ Auth::user()->organization->name }}</span>
        </li>
      @else
        <li class="title">Organizer: {{ Auth::user()->display_name }}</li>
      @endif


      <li>
        @php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'competition' ? 'active' : false; @endphp
        <a href="{{ route('organizer.competition.index') }}" class="{{ $link_class }}">Competitions</a>
      </li>
      @can('showAll','App\User')
        <li>
          @php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'user' ? 'active' : false; @endphp
          <a href="{{ route('organizer.user.index') }}" class="{{ $link_class }}">Users</a>
        </li>
      @endcan
      @can('showAll','App\Penalty')
        <li>
          @php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'penalty' ? 'active' : false; @endphp
          <a href="{{ route('organizer.penalty.index') }}" class="{{ $link_class }}">Penalties</a>
        </li>
      @endcan
      @can('showAll','App\Award')
        <li>
          @php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'award' ? 'active' : false; @endphp
          <a href="{{ route('organizer.award.index') }}" class="{{ $link_class }}">Awards</a>
        </li>
      @endcan
      <li>
        @php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'organization' ? 'active' : false; @endphp
        <a href="{{ route('organizer.organization.show') }}" class="{{ $link_class }}">Organization</a>
      </li>

    </ul>

    @if (Auth::user()->isAdmin() == false)
      <ul class="user-actions navigation">
        <li>
          @php $link_class = Request::segment(1) == 'profile' ? 'active' : false; @endphp
          <a href="{{ route('profile.edit') }}" class="{{ $link_class }}">My Profile</a>
        </li>
        <li>
          <a href="{{ url('logout') }}">Logout</a>
        </li>
      </ul>
    @endif
  </div>
</div>
