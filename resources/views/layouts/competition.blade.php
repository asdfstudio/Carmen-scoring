@extends('layouts.app')


@section('content')
  <div class="competition-header-wrap">
    <div class="competition-header body-width">
      @section('competition_header')
        <div class="heading">
          <h1>{{ $competition->name }}</h1>
        </div>
        <div class="subheading">
          <h4>{{ $competition->place->city_state() }}</h4>
          <h5 class="pull-right">July 22, 2016</h5>
        </div>
      @show
    </div>
  </div>

  <div class="competition-content-wrap body-width">
    <div class="competition-sidebar">
      @section('competition_sidebar')

        @if(Auth::user()->isOrganizer())
          @include('competition_division.organizer.links',['divisions' => $competition->divisions])
        @endif

        @if(Auth::user()->isJudge())
          @include('competition_division.judge.links',['divisions' => $competition->divisions])
        @endif
      @show
    </div>

    <div class="competition-content">
      @yield('breadcrumbs')
      @yield('competition_content')
    </div>
  </div>
@endsection
