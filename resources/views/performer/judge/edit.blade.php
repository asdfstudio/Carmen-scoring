@extends('layouts.simple')


@section('content-header')
	<h1>{{ $soloDivision->name }}</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('judge.competition.solo-division.show','Back to Solo Division',[$competition, $soloDivision],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

		<h2>Set the performer's name</h2>

    <ul class="list-group">
      <li class="list-group-item">School / Choir: {{ $performer->choir->full_name }}</li>
    </ul>
    {!! form($form) !!}

@endsection
