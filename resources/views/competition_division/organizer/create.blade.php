@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.create',$competition) !!}
@endsection

@section('content-header')
	<h1>Create a division</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.division.index','Back to All Divisions',[$competition],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')
		{!! form($form) !!}
@endsection
