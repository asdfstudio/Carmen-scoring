@extends('layouts.app')

@section('style')
  @hasSection('style')
    @yield('style')
  @endif
@endsection

@section('body-content')
  @include('navigation/header')

  @if(empty($competition) AND isset($division->competition))
    @php $competition = $division->competition;@endphp
  @endif

  @if (isset($competition))
  <div class="competition-bar-wrap">
    <div class="competition-bar body-width">
      <div class="carmen-logo-wrap">
        <img src="/images/logo-with-dark-text.png" width="185" />
      </div>
      <div class="heading">
        {{ link_to_route(Request::segment(1) . '.competition.show', $competition->name, [$competition]) }}
        @if($competition->place)
          <span class="subheading">{{ $competition->place->city }}, {{ $competition->place->state }}</span>
        @endif
      </div>

      <div class="status-container">
        {!! $competition->status_label('pull-right') !!}
      </div>

    </div>
  </div>
  @endif

  @if (isset($division))
  <div class="division-bar body-width">
    <div class="heading">
      {{ link_to_route( Request::segment(1) . '.competition.division.show', $division->name, [$division->competition, $division])}}
      {!! $division->status_label() !!}
    </div>
    <div class="division-actions">
      <ul class="actions-group">
        <li>
          {{ link_to_route( Request::segment(1) . '.competition.show', 'All Divisions', [$division->competition], ['class' => 'action'])}}
        </li>
      </ul>
    </div>
  </div>
  @endif

  @section('division_navigation_bar')
    @if (isset($division))
      <div class="division-navigation-bar body-width">
        <ul class="division-navigation">
          <li>
            @php $link_class = in_array(Request::segment(6),['overview']) ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.show', [$competition, $division]) }}" class="{{ $link_class }}">Overview</a>
          </li>
          <li>
            @php $link_class = in_array(Request::segment(6),['settings','edit']) ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.settings', [$competition, $division]) }}" class="{{ $link_class }}">Settings</a>
          </li>
          <li>
            @php $link_class = Request::segment(6) == 'choir' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.choir.index', [$competition, $division]) }}" class="{{ $link_class }}">Choirs
              <span class="count">{{ $division->choirs->count() }}</span>
            </a>
          </li>
          <li>
            @php $link_class = Request::segment(6) == 'judge' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.judge.index', [$competition, $division]) }}" class="{{ $link_class }}">Judges
              <span class="count">{{ $division->judges->unique('id')->count() }}</span></a>
          </li>
          <li>
            @php $link_class = Request::segment(6) == 'round' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.round.index', [$competition, $division]) }}" class="{{ $link_class }}">Rounds
            <span class="count">{{ $division->rounds->count() }}</span>
            </a>
          </li>
          <li>
            @php $link_class = Request::segment(6) == 'penalty' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.penalty.index', [$competition, $division]) }}" class="{{ $link_class }}">Penalties
            <span class="count">{{ $division->penalties->count() }}</span>
            </a>
          </li>
          <li>
            @php $link_class = Request::segment(6) == 'award' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.award.index', [$competition, $division]) }}" class="{{ $link_class }}">Awards
              <span class="count">{{ $division->awards->count() }}</span>
            </a>
          </li>
          <li>
            @php $link_class = Request::segment(6) == 'standing' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.standing.show', [$competition, $division]) }}" class="{{ $link_class }}">Final Standings</a>
          </li>
          <li>
            @php $link_class = Request::segment(6) == 'ceremony' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.ceremony.show', [$competition, $division]) }}" class="{{ $link_class }}">Award Ceremony</a>
          </li>

        </ul>
      </div>
    @endif
  @show

  @section('round_navigation_bar')

  @show

  <div class="collapse content body-width">

    @hasSection('content-header')
      <div class="content-header">
        @yield('content-header')
      </div>
    @endif

    @section('alert')
      @include('alert/all')
    @show

    @yield('content')
  </div>

  @hasSection('breadcrumbs')
    <div class="breadcrumbs-footer body-width">
      @yield('breadcrumbs')
    </div>
  @endif

@endsection

@push('own-script')
  @stack('own-script')
@endpush
