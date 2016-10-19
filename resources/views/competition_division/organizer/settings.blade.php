@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.show',$competition,$division) !!}
@endsection

@section('content-header')
	<h1>Scoring Settings</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.division.edit','Edit Division',[$competition, $division],['class' => 'action']) }}</li>
	</ul>


@endsection

@section('content')

		@include('division.partial.single')

@endsection
