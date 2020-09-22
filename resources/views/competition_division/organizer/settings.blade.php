@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.show',$competition,$division) !!}
@endsection

@section('content-header')
	<h1>Scoring Settings</h1>

	@can('update', $division)
		<ul class="actions-group">
			<li>{{ link_to_route('organizer.competition.division.edit','Edit scoring settings',[$competition, $division],['class' => 'action']) }}</li>
			<li>{{ link_to_route('organizer.competition.division.board', 'Edit choirs, judges, rounds', [$competition,$division],['class' => 'action']) }}</li>
			<li>{{ link_to_route('organizer.competition.division.award.settings.edit','Edit Award Settings',[$competition, $division],['class' => 'action']) }}</li>
		</ul>
	@endcan

@endsection

@section('content')
	<!-- dg -->
	@can('viewResults', $division)
		<div class="alert alert-info d-flex">
			<i class="fa fa-commenting dg-fs-20 mr"></i>
			<p>Results for this division are available at {{ link_to_route('results.division.show', NULL, [$division, $division->access_code], ['target' => '_blank']) }} </p>
		</div>
	@endcan

		@include('division.partial.single')

		<h3>Award Settings</h3>
		
		@include('division_award_settings.organizer.list', ['awardSettings' => $division->awardSettings])

@endsection
