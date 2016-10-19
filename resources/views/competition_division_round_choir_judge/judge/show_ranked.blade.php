@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('round.scores.choir.judge.show.ranked',$round->division->competition,$round->division,$round,$choir,$judge) !!}
	
  @include('division.partial.single',['division' => $round->division])
  
  @include('round.status_message')
  
  @include('scores.judge_ranked',['division' => $round->division, 'judge' => $round->division->judges->first()])
  
@endsection