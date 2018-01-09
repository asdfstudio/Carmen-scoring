@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.schedule.index', $competition) !!}
@endsection

@section('content-header')
  <h1>Create a Schedule</h1>
@endsection

@section('content')

  {!! form($form) !!}

@endsection
