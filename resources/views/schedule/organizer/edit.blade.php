@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.schedule.edit', $competition, $schedule) !!}
@endsection

@section('content')

  <h1>Edit {{ $schedule->name }}</h1>

  {!! form($form) !!}

@endsection
