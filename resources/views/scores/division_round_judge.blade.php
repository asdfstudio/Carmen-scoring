@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('competition.division.round.judge.show',$division->competition, $division, $round, $judge) !!}

	<h1>Scores for {{ $judge->full_name }}</h1>
  <h2>Division: {{ link_to_route('competition.division.show',$division->name,[$division->competition,$division]) }}</h2>
	
  @include('scores.judge_raw')
  
@endsection