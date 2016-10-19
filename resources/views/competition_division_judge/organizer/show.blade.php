@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('organizer.competition.division.judge.show',$division->competition,$division, $judge) !!}

	<h1>{{ $judge->full_name }}</h1>

  {!! form($form) !!}

@endsection
