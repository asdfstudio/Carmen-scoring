@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('round.scores.mine.ranked',$round->division->competition,$round->division,$round) !!}
	
  @include('division.partial.single',['division' => $round->division])
  
  {{ link_to_route('competition.division.round.show','Show Raw Scores',[$round->division->competition,$round->division,$round]) }}
  
  @include('round.status_message')
  
  @include('scores.judge_ranked',['division' => $round->division, 'judge' => $round->division->judges->first()])
@endsection