@extends('layouts.simple')

@section('title')
  {{ $competition->name }} | @parent
@endsection

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.show',$competition) !!}
@endsection

@section('content-header')
  <h1>Competition Details</h1>

  @can('update', $competition)
    {{ link_to_route('organizer.competition.edit', 'Edit Competition', [$competition], ['class' => 'action']) }}
  @endif

@endsection



@section('content')

  <ul class="actions-group mv">
    @can('activateScoring', $competition)
      <li>{!! form($activateScoringForm) !!}</li>
    @endcan
    @can('archiveCompetition', $competition)
      <li>{!! form($archiveCompetitionForm) !!}</li>
    @endcan
    @can('completeScoring', $competition)
      <li>{!! form($completeScoringForm) !!}</li>
    @endcan
  </ul>

  <h3>Competition Results</h3>

  <ul>
    <li>Results URL: {{ link_to($competition->results_url) }}</li>
    <li>Access Code: {{ $competition->access_code }}</li>
  </ul>


  <h3>Manage Divisions</h3>
  <p>Divisions are used to organize your competition and consist of choirs, judges, scoring settings and more.</p>

  <p>{{ link_to_route('organizer.competition.division.index','Manage your divisions',[$competition], ['class' => 'action']) }}</p>

  @if($competition->divisions->count() > 0)

    @include('division.organizer.list',['divisions' => $competition->divisions])

  @else
    <p>{{ link_to_route('organizer.competition.division.create','Create your first division',[$competition]) }}</p>
  @endif

  <h3>Manage Schedules</h3>

  <p>Set the performance order for your competition. Do this after you have created all of your divisions, rounds and choirs.</p>

  <p>{{ link_to_route('organizer.competition.schedule.index','Manage your schedules',[$competition], ['class' => 'action']) }}</p>

  <h3>Manage Award Ceremony Schedules</h3>

  <p>Set the schedule for your award ceremonies..</p>

  <p>{{ link_to_route('organizer.competition.award-schedule.index','Manage your award ceremony schedules',[$competition], ['class' => 'action']) }}</p>


@endsection
