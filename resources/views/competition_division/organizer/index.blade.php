@extends('layouts.simple')

@section('breadcrumb')
	{!! Breadcrumbs::render('organizer.competition.division.index',$competition) !!}
@endsection

@section('content-header')
	<h1>Manage Divisions</h1>

	{{ link_to_route('organizer.competition.division.create','Add a division',[$competition],['class' => 'btn btn-primary']) }}
@endsection

@section('content')

  @include('competition_division.organizer.table',['divisions' => $competition->divisions])

@endsection
