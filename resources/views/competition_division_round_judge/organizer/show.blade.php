@extends('layouts.simple')

@section('content-header')
  <h1>Scores for {{ $judge->full_name }}</h1>
@endsection


@section('content')

  @include('scores.organizer.judge_raw')

@endsection
