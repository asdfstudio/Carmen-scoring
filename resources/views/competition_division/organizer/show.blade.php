@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.show',$competition,$division) !!}
@endsection

@section('content')

	<ul class="actions-group mv">
		@can('activateScoring', $division->rounds()->first())
			<li>{!! form($activateScoringForm) !!}</li>
		@endcan

		@can('reactivateScoring', $division->rounds()->first())
      <li>
        {!! form($reactivateScoringForm) !!}
      </li>
    @endcan

    @can('deactivateScoring', $division->rounds()->first())
      <li>
        {!! form($deactivateScoringForm) !!}
      </li>
    @endcan

		@can('completeScoring', $division->rounds()->first())
			<li>{!! form($completeScoringForm) !!}</li>
		@endcan

		@can('finalizeScoring', $division)
			<li>{!! form($finalizeScoringForm) !!}</li>
		@endcan

		@can('update', $division)
			<li>{{ link_to_route('organizer.competition.division.edit', 'Edit Division', [$competition,$division],['class' => 'action']) }}</li>
			<li>{{ link_to_route('organizer.competition.division.board', 'Enter Set Up Mode', [$competition,$division],['class' => 'action']) }}</li>
		@endcan

	</ul>

	<div class="clearfix"></div>

	@if ($division->isMissingScores())
		<p class="alert alert-warning">At least one round of this division is currently missing scores. Do not complete the scoring until you have received scores from all judges.</p>
	@endif

	@if($division->status_slug() == 'finalized')
		<div class="alert alert-info">
			<p>Results for this division are available at {{ link_to_route('results.division.show', NULL, [$division, $division->access_code], ['target' => '_blank']) }} </p>
		</div>
	@endif

	<ul class="list-group">
		<li class="list-group-item">
			<h3>Settings</h3>
			<p>{{ link_to_route('organizer.competition.division.settings', 'Manage scoring settings', [$competition, $division]) }}</p>
			<p>{{ link_to_route('organizer.competition.division.award.settings.edit','Edit Award Settings',[$competition, $division]) }}</p>
		</li>
		<li class="list-group-item">
			<h3>Choirs</h3>
			<p>{{ link_to_route('organizer.competition.division.choir.index', 'Manage choirs', [$competition, $division]) }}</p>
		</li>
		<li class="list-group-item">
			<h3>Judges</h3>
			<p>{{ link_to_route('organizer.competition.division.judge.index', 'Manage judges', [$competition, $division]) }}</p>
		</li>
		<li class="list-group-item">
			<h3>Rounds</h3>
			<p>{{ link_to_route('organizer.competition.division.round.index', 'Manage rounds', [$competition, $division]) }}</p>
		</li>
		<li class="list-group-item">
			<h3>Penalties</h3>
			<p>{{ link_to_route('organizer.competition.division.penalty.index', 'Manage penalties', [$competition, $division]) }}</p>
		</li>
		<li class="list-group-item">
			<h3>Awards</h3>
			<p>{{ link_to_route('organizer.competition.division.award.index', 'Manage awards', [$competition, $division]) }}</p>
		</li>
	</ul>

	<div data-tab-id="scoring" class="tab-content">
		@include('division.partial.single')
	</div>




  <div class="row">

    <div data-tab-id="rounds" class="tab-content col-xs-12 col-sm-12">

      <h3>{{ link_to_route('organizer.competition.division.round.index','Rounds',[$competition,$division]) }} ({{ $division->rounds->count() }})</h3>

      @include('competition_division_round.organizer.table')

			{{ link_to_route('organizer.competition.division.round.create','Add a round',[$competition,$division],['class' => 'btn btn-primary']) }}

			{{ link_to_route('organizer.competition.division.round.setup','Set up rounds',[$competition,$division],['class' => 'btn btn-primary']) }}


    </div>

    <div data-tab-id="choirs" class="tab-content col-xs-12 col-sm-12">

      <h3>{{ link_to_route('organizer.competition.division.choir.index','Choirs',[$competition,$division]) }} ({{ $division->choirs->count() }})</h3>

      @include('competition_division_choir.organizer.table')

      {{ link_to_route('organizer.competition.division.choir.create','Add a choir',[$competition,$division],['class' => 'btn btn-primary']) }}

			{{ link_to_route('organizer.competition.division.choir.setup','Set up choir',[$competition,$division],['class' => 'btn btn-primary']) }}

    </div>

    <div data-tab-id="judges" class="tab-content col-xs-12 col-sm-12">

    	<h3>{{ link_to_route('organizer.competition.division.judge.index','Judges',[$competition,$division]) }} ({{ $division->judges->count() }})</h3>

    	@include('competition_division_judge.organizer.table',['judges' => $division->judges])

      {{ link_to_route('organizer.competition.division.judge.create','Add a judge',[$competition,$division],['class' => 'btn btn-primary']) }}

			{{ link_to_route('organizer.competition.division.judge.setup','Set up judges',[$competition,$division],['class' => 'btn btn-primary']) }}

    </div>


		<div data-tab-id="awards" class="tab-content col-xs-12 col-sm-12">

    	<h3>{{ link_to_route('organizer.competition.division.award.index', 'Awards', [$competition,$division]) }} ({{ $division->awards->count() }})</h3>

    	@include('award.organizer.list', ['awards' => $division->awards])

    </div>

		<div data-tab-id="penalties" class="tab-content col-xs-12 col-sm-12">

    	<h3>{{ link_to_route('organizer.competition.division.penalty.index','Penalties', [$competition,$division]) }} ({{ $division->penalties->count() }})</h3>

    	@include('penalty.organizer.list', ['penalties' => $division->penalties])

    </div>

  </div>

@endsection
