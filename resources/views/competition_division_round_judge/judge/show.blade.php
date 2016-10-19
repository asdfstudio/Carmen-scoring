@extends('layouts.app')

@section('content')


  @include('division.partial.single',['division' => $round->division])

  @include('round.status_message')

  @include('scores.judge_raw',['division' => $round->division, 'judge' => $round->division->judges->first()])

@endsection
