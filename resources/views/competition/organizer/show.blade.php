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

  <h3>Group divisions</h3>
  <p>Rounds are groups of divisions that use the same scoresheet, scoring method, and judges.</p>

  @if($roundsCount > 0)
      <p>{{ link_to_route('organizer.competition.round.index','Manage your rounds',[$competition], ['class' => 'action']) }}
          {{ link_to_route('organizer.competition.division.index','Manage your divisions',[$competition], ['class' => 'action']) }}
      </p>
      @include('round.organizer.list',['rounds' => $competition->rounds])
  @else
      <p>{{ link_to_route('organizer.competition.round.create','Create your first round',[$competition], ['class' => 'action']) }}</p>
  @endif

{{--  <h3>Manage Divisions</h3>--}}
{{--  <p>Divisions are used to organize competitors and division specific awards.</p>--}}

{{--  @if($divisionCount > 0)--}}
{{--      <p>{{ link_to_route('organizer.competition.division.index','Manage your divisions',[$competition], ['class' => 'action']) }}</p>--}}
{{--      @include('division.organizer.list',['divisions' => $competition->divisions, 'scoringForms' => $divisionScoringForms])--}}
{{--  @elseif($roundsCount > 0)--}}
{{--      <p>{{ link_to_route('organizer.competition.division.create','Create your first division',[$competition], ['class' => 'action']) }}</p>--}}
{{--  @else--}}
{{--      <p>{{ link_to_route('organizer.competition.round.create','Create your first round to add divisions',[$competition], ['class' => 'action']) }}</p>--}}
{{--  @endif--}}

  <h3>Manage Solo Divisions</h3>

  @if($competition->soloDivisions->count() > 0)
      <p>{{ link_to_route('organizer.competition.solo-division.create','Create a solo division',[$competition], ['class' => 'action']) }}</p>
      @include('solo-division.organizer.list',['soloDivisions' => $competition->soloDivisions, 'scoringForms' => $divisionScoringForms])
  @else
      <p>{{ link_to_route('organizer.competition.solo-division.create','Create your first solo division',[$competition], ['class' => 'action']) }}</p>
  @endif

  <h3>Manage Competition Awards</h3>
  <ul class="actions-group">
    @can('createForCompetition', ['App\Award', $competition])
        <li>
            {{ link_to_route('organizer.competition.award.create','Create competition award', [$competition->id], ['class' => 'action']) }}
        </li>
    @endcan
    @can('manage' , ['App\Award', $competition])
        <li>
            {{ link_to_route('organizer.competition.award.manage','Manage competition awards', [$competition->id], ['class' => 'action']) }}
        </li>
    @endcan

    @can('assign' , ['App\Award', $competition])
        <li>
            {{ link_to_route('organizer.competition.award.assign', 'Assign awards', [$competition->id], ['class' => 'action']) }}
        </li>
    @endcan
  </ul><br>

  {{-- Awards will appear here  --}}
  @include('award.organizer.list')

  <h3>Manage Schedules</h3>

  <p>Set the performance order for your competition. Do this after you have created all of your divisions, rounds and ensembles.</p>

  <p>{{ link_to_route('organizer.competition.schedule.create','Add a performance schedule',[$competition], ['class' => 'action']) }}</p>

  @include('schedule.organizer.table', ['schedules' => $competition->schedules])

  <h3>Manage Award Ceremony Schedules</h3>

  <p>Set the schedule for your award ceremonies.</p>

  <p>{{ link_to_route('organizer.competition.award-schedule.create','Add an award ceremony schedule',[$competition], ['class' => 'action']) }}</p>

  @include('award-schedule.organizer.table', ['schedules' => $competition->awardSchedules])


  <h3>Feedback Links</h3>
  <p>View the URLs where ensemble directors can view feedback from judges.</p>
  <p>{{ link_to_route('organizer.competition.comment-links.index','View feedback links',[$competition], ['class' => 'action']) }}</p>

@endsection
