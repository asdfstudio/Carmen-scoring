
@if (!Auth::guest())
  @if (Auth::user()->isAdmin())
    @include('navigation/admin/header')
  @endif

  @if (Auth::user()->isOrganizer() AND Request::segment(1) != 'admin')
    @include('navigation/organizer/header')
  @endif

  @if (Auth::user()->isJudge())
    @include('navigation/judge/header')
  @endif
@endif
