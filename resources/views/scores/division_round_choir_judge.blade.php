@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('competition.division.round.choir.judge.show',$division->competition, $division, $round, $choir, $judge) !!}

	<h1>Scores for {{ $choir->name }} by {{ $judge->full_name }}</h1>
  <h2>Division: {{ link_to_route('competition.division.show',$division->name,[$division->competition,$division]) }}, {{ $round->name }}</h2>
	
  @include('scores.choir_judge_raw',['sheet' => $division->sheet])
  
@endsection