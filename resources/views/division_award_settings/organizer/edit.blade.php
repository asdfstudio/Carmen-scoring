@extends('layouts.simple')

@section('breadcrumbs')

@endsection

@section('content-header')
	<h1>Award Settings</h1>

	@can('update', $division)
		<ul class="actions-group">
			<li>{{ link_to_route('organizer.competition.division.show','Back to Division',[$competition, $division],['class' => 'action']) }}</li>
		</ul>
	@endcan


@endsection

@section('content')

  {!! form($form) !!}

@endsection
