@extends('layouts.simple')

@section('title')
  {{ $competition->name }} | @parent
@endsection

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.show',$competition) !!}
@endsection

@section('content-header')
  <h1>Competition Dashboard</h1>

  @can('update', $competition)
    {{ link_to_route('organizer.competition.edit', 'Edit Competition', [$competition], ['class' => 'action']) }}
  @endif


@endsection



@section('content')

  <ul class="actions-group mv">
    @can('activateCompetition', $competition)
      <li>{!! form($activateScoringForm) !!}</li>
    @endcan
    @can('archiveCompetition', $competition)
      <li>{!! form($archiveCompetitionForm) !!}</li>
    @endcan
    @can('closeCompetition', $competition)
      <li>{!! form($completeScoringForm) !!}</li>
    @endcan
  </ul>

  <h3>Competition Results</h3>

  <ul class="list-group">
    <li class="list-group-item">Results URL: {{ link_to($competition->results_url) }}</li>
    <li class="list-group-item">Access Code: {{ $competition->access_code }}</li>
  </ul>

  <h3>Manage Rounds</h3>
  <p>Rounds are a group of divisions that utilize the same scoring system.</p>

  @if($roundsCount > 0)
      <p>{{ link_to_route('organizer.competition.round.index','Manage your rounds',[$competition], ['class' => 'action']) }}</p>
      @include('round.organizer.list',['rounds' => $competition->rounds])
  @else
      <p>{{ link_to_route('organizer.competition.round.create','Create your first round',[$competition], ['class' => 'action']) }}</p>
  @endif

  <h3>Manage Divisions</h3>
  <p>Divisions are used to organize your competition and consist of choirs and judges.</p>

  @if($divisionCount > 0)
      <p>{{ link_to_route('organizer.competition.division.index','Manage your divisions',[$competition], ['class' => 'action']) }}</p>
      @include('division.organizer.list',['divisions' => $competition->divisions, 'scoringForms' => $divisionScoringForms])
  @elseif($roundsCount > 0)
      <p>{{ link_to_route('organizer.competition.division.create','Create your first division',[$competition], ['class' => 'action']) }}</p>
  @else
      <p>{{ link_to_route('organizer.competition.round.create','Create your first round to add divisions',[$competition], ['class' => 'action']) }}</p>
  @endif

  <h3>Manage Solo Divisions</h3>

  @if($competition->soloDivisions->count() > 0)
      <p>{{ link_to_route('organizer.competition.solo-division.create','Create a solo division',[$competition], ['class' => 'action']) }}</p>
      @include('solo-division.organizer.list',['soloDivisions' => $competition->soloDivisions])
  @else
      <p>{{ link_to_route('organizer.competition.solo-division.create','Create your first solo division',[$competition], ['class' => 'action']) }}</p>
  @endif

  <h3>Manage Schedules</h3>

  <p>Set the performance order for your competition. Do this after you have created all of your divisions, rounds and choirs.</p>

  <p>{{ link_to_route('organizer.competition.schedule.create','Add a performance schedule',[$competition], ['class' => 'action']) }}</p>

  @include('schedule.organizer.table', ['schedules' => $competition->schedules])

  <h3>Manage Award Ceremony Schedules</h3>

  <p>Set the schedule for your award ceremonies.</p>

  <p>{{ link_to_route('organizer.competition.award-schedule.create','Add an award ceremony schedule',[$competition], ['class' => 'action']) }}</p>

  @include('award-schedule.organizer.table', ['schedules' => $competition->awardSchedules])


  <h3>Feedback Links</h3>
  <p>View the URLs where choir directors can view feedback from judges.</p>
  <p>{{ link_to_route('organizer.competition.comment-links.index','View feedback links',[$competition], ['class' => 'action']) }}</p>

@endsection
