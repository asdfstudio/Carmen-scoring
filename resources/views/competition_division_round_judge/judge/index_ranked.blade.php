@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('round.scores.ranked',$round->division->competition,$round->division,$round) !!}
	
  @include('division.partial.single',['division' => $round->division])
  
  @include('round.status_message')
  
  @include('scores.choirs_judges_aggregate_ranked',['round' => $round,'choirs' => $round->division->choirs,'judges' => $round->division->judges])
  
@endsection