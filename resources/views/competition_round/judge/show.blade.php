@extends('layouts.app')

@section('content')


  @include('division.partial.single',['division' => $round->division])

  {{ link_to_route('round.scores.mine.ranked','Show Ranked Scores',[$round->division->competition,$round->division,$round]) }}

  @include('round.status_message')

  @if($round->is_scoring_active)
  	@include('scores.forms.judge_raw',['division' => $round->division, 'judge' => $round->division->judges->first()])
  @endif

  @if(! $round->is_scoring_active)
  	@include('scores.judge_raw',['division' => $round->division, 'judge' => $round->division->judges->first()])
  @endif
@endsection
