@extends('layouts.competition')



@section('competition_content')

  <div class="division_container row">

    <div class="division_header col-xs-12">
      @section('division_header')
        <h1><small>Division:</small> {{ $division->name }}</h1>
      @show
    </div>

    <div class="division_sidebar col-xs-12">
      @section('division_sidebar')

        @if(Auth::user()->isOrganizer())
          @include('competition_division_round.organizer.links',['divisions' => $competition->divisions])
        @endif
      @show
    </div>

    <div class="division_content col-xs-12">
      @yield('division_content')
    </div>
  </div>
@endsection
