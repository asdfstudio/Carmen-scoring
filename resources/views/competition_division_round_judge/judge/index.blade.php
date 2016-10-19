@extends('layouts.app')

@section('content')


  @include('division.partial.single',['division' => $round->division])

  @include('round.status_message')

  @include('scores.choirs_judges_aggregate',['round' => $round,'choirs' => $round->division->choirs,'judges' => $round->division->judges])

@endsection
