@extends('layouts.app')


@include('navigation/header')

@if(empty($competition) AND isset($division->competition))
  <?php $competition = $division->competition;?>
@endif

@if (isset($competition))
<div class="competition-bar-wrap">
  <div class="competition-bar body-width">
    <div class="carmen-logo-wrap">
      <img src="/images/Carmen-Logo-185x60.png"  />
    </div>
    <div class="heading">
      {{ $competition->name }}
      <span class="subheading">{{ $competition->place->city }}, {{ $competition->place->state }}</span>
    </div>
  </div>
</div>
@endif

@if (isset($division))
<div class="division-bar body-width">
  <div class="heading">
    {{ link_to_route( Request::segment(1) . '.competition.division.show', $division->name, [$division->competition, $division])}}
    <small>{{ $division->status() }}</small>
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
        <!--<li>
          <a href="#overview">Overview</a>
        </li>-->
        <li>
          <?php $link_class = in_array(Request::segment(6),['settings','edit']) ? 'active' : false; ?>
          <a href="{{ route('organizer.competition.division.settings', [$competition, $division]) }}" class="{{ $link_class }}">Settings</a>
        </li>
        <li>
          <?php $link_class = Request::segment(6) == 'choir' ? 'active' : false; ?>
          <a href="{{ route('organizer.competition.division.choir.index', [$competition, $division]) }}" class="{{ $link_class }}">Choirs</a>
        </li>
        <li>
          <?php $link_class = Request::segment(6) == 'judge' ? 'active' : false; ?>
          <a href="{{ route('organizer.competition.division.judge.index', [$competition, $division]) }}" class="{{ $link_class }}">Judges</a>
        </li>
        <li>
          <?php $link_class = Request::segment(6) == 'round' ? 'active' : false; ?>
          <a href="{{ route('organizer.competition.division.round.index', [$competition, $division]) }}" class="{{ $link_class }}">Rounds</a>
        </li>
        <li>
          <?php $link_class = Request::segment(6) == 'penalty' ? 'active' : false; ?>
          <a href="{{ route('organizer.competition.division.penalty.index', [$competition, $division]) }}" class="{{ $link_class }}">Penalties</a>
        </li>
        <li>
          <?php $link_class = Request::segment(6) == 'award' ? 'active' : false; ?>
          <a href="{{ route('organizer.competition.division.award.index', [$competition, $division]) }}" class="{{ $link_class }}">Awards</a>
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
