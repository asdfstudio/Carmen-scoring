@extends('layouts.simple')


@section('content-header')
	<h1>Edit solo division</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.solo-division.show','Back to Solo Division',[$competition, $soloDivision],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')
		{!! form($form) !!}
@endsection
