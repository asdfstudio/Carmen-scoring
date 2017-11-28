@extends('layouts.simple')

@section('breadcrumb')
	{!! Breadcrumbs::render('organizer.competition.schedule.index',$competition) !!}
@endsection

@section('content-header')
	<h1>Manage Schedules</h1>

	{{ link_to_route('organizer.competition.schedule.create', 'Add a schedule', [$competition], ['class' => 'action']) }}

@endsection

@section('content')

  @include('schedule.organizer.table',['schedules' => $competition->schedules])

@endsection
