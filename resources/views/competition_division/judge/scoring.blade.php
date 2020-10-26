@extends('layouts.simple')


@section('division_navigation_bar')

@endsection

@section('content')

  <h3>Scoring Mode</h3>

  <h4>Select Round to Score</h4>

  @include('competition_round.judge.list', ['round' => $division->round])


@endsection
