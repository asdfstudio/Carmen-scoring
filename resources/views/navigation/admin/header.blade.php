<div class="header-wrap admin">
  <div class="header body-width">
    <ul class="navigation">
      <li class="title">Admin</li>
      <li>
        @php $link_class = Request::segment(2) == 'organization' ? 'active' : false; @endphp
        <a href="{{ route('admin.organization.index') }}" class="{{ $link_class }}">Organizations</a>
      </li>
      <li>
        @php $link_class = Request::segment(2) == 'user' ? 'active' : false; @endphp
        <a href="{{ route('admin.user.index') }}" class="{{ $link_class }}">Users</a>
      </li>
      <li>
        @php $link_class = Request::segment(2) == 'choir' ? 'active' : false; @endphp
        <a href="{{ route('admin.choir.index') }}" class="{{ $link_class }}">Choirs</a>
      </li>
      <li>
        @php $link_class = Request::segment(2) == 'school' ? 'active' : false; @endphp
        <a href="{{ route('admin.school.index') }}" class="{{ $link_class }}">Schools</a>
      </li>

      <li>
        @php $link_class = Request::segment(2) == 'sheet' ? 'active' : false; @endphp
        <a href="{{ route('admin.sheet.index') }}" class="{{ $link_class }}">Sheets</a>
      </li>

      <li>
        @php $link_class = Request::segment(2) == 'criteria' ? 'active' : false; @endphp
        <a href="{{ route('admin.criteria.index') }}" class="{{ $link_class }}">Criteria</a>
      </li>

      <li>
        @php $link_class = Request::segment(2) == 'caption' ? 'active' : false; @endphp
        <a href="{{ route('admin.caption.index') }}" class="{{ $link_class }}">Captions</a>
      </li>

      <li>
        @php $link_class = Request::segment(2) == 'raw-score-log' ? 'active' : false; @endphp
        <a href="{{ route('admin.raw-score-log.index') }}" class="{{ $link_class }}">Logs</a>
      </li>

      @if(env('IS_WORKSHOP_ENABLED'))
        <li>
          @php $link_class = Request::segment(2) == 'workshop' ? 'active' : false; @endphp
          <a href="{{ route('workshop.index') }}" class="{{ $link_class }}">Workshop</a>
        </li>
      @endif


    </ul>

    <ul class="user-actions navigation">
      <li>
        @php $link_class = Request::segment(1) == 'profile' ? 'active' : false; @endphp
        <a href="{{ route('profile.edit') }}" class="{{ $link_class }}">My Profile</a>
      </li>
      <li>
        <a href="{{ url('logout') }}">Logout</a>
      </li>
    </ul>
  </div>
</div>
