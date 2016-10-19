@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('round.scores.judge.show.ranked',$round->division->competition,$round->division,$round,$judge) !!}
	
  @include('division.partial.single',['division' => $round->division])
  
  @include('scores.judge_ranked',['division' => $round->division, 'judge' => $round->division->judges->first()])
  
@endsection