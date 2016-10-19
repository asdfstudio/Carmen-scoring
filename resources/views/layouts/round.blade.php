@extends('layouts.division')

@section('division_content')

  <div class="round_container row">

    <div class="round_header col-xs-12">
      @section('round_header')
        <h1><small>Round:</small> {{ $round->name }}</h1>
        <span class="pull-right label label-default">{{ $round->status() }}</span>

      @show
    </div>

    <div class="round_sidebar col-xs-12">
      @section('round_sidebar')
      @show
    </div>

    <div class="round_content col-xs-12">
      @yield('round_content')
    </div>

</div>

@endsection
