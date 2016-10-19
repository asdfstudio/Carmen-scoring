@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('competition.division.round.choir.show',$division->competition, $division, $round, $choir) !!}

	<h1>Scores for {{ $choir->name }}</h1>
  <h2>Division: {{ link_to_route('competition.division.show',$division->name,[$division->competition,$division]) }}</h2>
	
  @include('scores.choir_raw')
  
@endsection