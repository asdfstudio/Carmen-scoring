@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.schedule.index', $competition) !!}
@endsection

@section('content')

  <h1>Create a Schedule</h1>

  {!! form($form) !!}

@endsection
