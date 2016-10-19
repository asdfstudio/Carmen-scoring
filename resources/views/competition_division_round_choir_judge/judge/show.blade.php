@extends('layouts.app')

@section('content')

  @include('division.partial.single',['division' => $round->division])

  @include('round.status_message')

  @if($round->is_scoring_active AND $judge->id == Auth::user()->person_id)

  	@include('scores.forms.judge_raw',['division' => $round->division, 'judge' => $round->division->judges->first()])

  @else

  	@include('scores.judge_raw',['division' => $round->division])

  @endif

@endsection
