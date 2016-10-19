@extends('layouts.simple')


@section('content-header')
  @parent
  <h1>Scores for {{ $choir->full_name }} by {{ $judge->full_name }}</hq>
@endsection

@section('content')

  @include('scores.choir_judge_raw',['division' => $round->division,'sheet' => $division->sheet])

@endsection
