@extends('layouts.simple')

@section('breadcrumb')
	{!! Breadcrumbs::render('organizer.competition.award-schedule.index',$competition) !!}
@endsection

@section('content-header')
	<h1>Manage Award Schedules</h1>

	{{ link_to_route('organizer.competition.award-schedule.create', 'Add a schedule', [$competition], ['class' => 'action']) }}

@endsection

@section('content')

  @include('award-schedule.organizer.table',['award-schedules' => $competition->awardSchedules])

@endsection
