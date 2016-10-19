@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.judge.index', $division->competition,$division) !!}
@endsection

@section('content-header')
	<h1>Manage Judges</h1>

	<ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.division.judge.create','Add a judge',[$division->competition,$division], ['class' => 'action']) }}
		</li>
	</ul>
@endsection


@section('content')



  @include('competition_division_judge.organizer.list',['judges' => $division->judges])

@endsection
