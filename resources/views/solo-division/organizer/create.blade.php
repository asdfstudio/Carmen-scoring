@extends('layouts.simple')


@section('content-header')
	<h1>Create a solo division</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.show','Back to Competition',[$competition],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')
		{!! form($form) !!}
@endsection
