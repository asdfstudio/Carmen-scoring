@extends('layouts.simple')

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.round.show',$competition, $round) !!}
@endsection

@section('content-header')
	<h1>{{ $round->name }}</h1>

	<ul class="actions-group">

		@can('showAll','App\Round')
			<li>{{ link_to_route('organizer.competition.round.index', 'Back to all Rounds', [$competition], ['class' => 'action']) }}</li>
		@endcan

		@can('update', $round)
			<li>
				{{ link_to_route('organizer.competition.round.edit', 'Edit', [$competition,$round], ['class' => 'action']) }}
			</li>
		@endcan

        {{--
		@can('activateScoring', $round)
			<li>
				{!! form($activateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
			</li>
		@endcan

		@can('reactivateScoring', $round)
      <li>
        {!! form($reactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
      </li>
    @endcan

		@can('deactivateScoring', $round)
			<li>
				{!! form($deactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
			</li>
		@endcan

		@can('completeScoring', $round)
			<li>
				{!! form($completeScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]) !!}
			</li>
		@endcan
        --}}
	</ul>
@endsection

@section('content')
	<ul class="actions-group mv">
		@can('completeScoring', $round)
			<li>{!! form($completeScoringForm) !!}</li>
		@endcan

		@can('update', $round)
			<li>{{ link_to_route('organizer.competition.round.edit', 'Edit Round', [$competition,$round],['class' => 'action']) }}</li>
		@endcan

	</ul>

	<div class="clearfix"></div>

	@if ($round->isMissingScores())
		<p class="alert alert-warning">This round is currently missing scores. Do not complete the scoring until you have received scores from all judges.</p>
	@endif

	@if($round->status_slug() == 'finalized')
		<div class="alert alert-info">
			<p>Results for this round are available at {{ link_to_route('results.round.show', NULL, [$round, $round->access_code], ['target' => '_blank']) }} </p>
		</div>
	@endif

	<ul class="list-group">
		<li class="list-group-item">
			<h3>Settings</h3>
			<p>{{ link_to_route('organizer.competition.round.settings', 'Manage scoring settings', [$competition, $round]) }}</p>
		</li>
		<li class="list-group-item">
			<h3>Divisions</h3>
{{--
			<p>{{ link_to_route('organizer.competition.round.division.index', 'Manage divisions', [$competition, $round]) }}</p>
--}}
		</li>
	</ul>

  <div class="row">
    <div data-tab-id="scoring" class="tab-content col-xs-12 col-sm-12">
		@include('round.partial.single')
	</div>

    <div data-tab-id="divisions" class="tab-content col-xs-12 col-sm-12">

{{--
      <h3>{{ link_to_route('organizer.competition.round.divisions.index','Division',[$competition,$round]) }} ({{ $round->divisions->count() }})</h3>

      @include('competition_division_round.organizer.table')

			{{ link_to_route('organizer.competition.division.round.create','Add a round',[$competition,$division],['class' => 'btn btn-primary']) }}

			{{ link_to_route('organizer.competition.division.round.setup','Set up rounds',[$competition,$division],['class' => 'btn btn-primary']) }}

--}}

    </div>

  </div>

@endsection
