@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('round.scores.choir.show.mine.ranked',$round->division->competition,$round->division,$round,$choir) !!}
	
  @include('division.partial.single',['division' => $round->division])
  
  @include('scores.choir_ranked',['division' => $round->division])
  
@endsection