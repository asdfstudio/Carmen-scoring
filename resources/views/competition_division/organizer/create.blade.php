@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.create',$competition) !!}
@endsection

@section('content-header')
	<h1>Create a division</h1>
@endsection

@section('content')
		{!! form($form) !!}
@endsection
