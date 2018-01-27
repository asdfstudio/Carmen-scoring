@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.show',$competition,$division) !!}
@endsection

@section('content-header')
	<h1>Scoring Settings</h1>

	@can('update', $division)
		<ul class="actions-group">
			<li>{{ link_to_route('organizer.competition.division.edit','Edit Division',[$competition, $division],['class' => 'action']) }}</li>
			<li>{{ link_to_route('organizer.competition.division.award.settings.edit','Edit Award Settings',[$competition, $division],['class' => 'action']) }}</li>
		</ul>
	@endcan


@endsection

@section('content')

		@include('division.partial.single')

		<h3>Award Settings</h3>
		
		@include('division_award_settings.organizer.list', ['awardSettings' => $division->awardSettings])

@endsection
