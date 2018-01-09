@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.award-schedule.edit', $competition, $schedule) !!}
@endsection

@section('content-header')
  <h1>Edit {{ $schedule->name }}</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.award-schedule.show', 'View Schedule', [$competition,$schedule], ['class' => 'action']) }}</li>
    <li>{{ link_to_route('organizer.competition.award-schedule.builder', 'Build Schedule', [$competition,$schedule], ['class' => 'action']) }}</li>
	</ul>

@endsection

@section('content')

  {!! form($form) !!}

@endsection
