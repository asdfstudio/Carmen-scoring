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

		@can('update', [$round])
            <li> {{ link_to_route('organizer.competition.round.edit', 'Edit Scoring', [$competition,$round], ['class' => 'action']) }} </li>
			<li>{{ link_to_route('organizer.competition.division.index', 'Edit Divisions', [$competition, $round], ['class' => 'action']) }}</li>
		@endcan
			<li>{{ link_to_route('organizer.competition.round.board', 'Edit Judges', [$competition, $round], ['class' => 'action']) }}</li>

	</ul>
@endsection

@section('content')
	<div class="clearfix"></div>

	@if ($round->isMissingScores())
		<p class="alert alert-warning">This round is currently missing scores. Do not complete the scoring until you have received scores from all judges.</p>
	@endif

	@if($round->status_slug() == 'finalized')
		<div class="alert alert-info">
			<p>Results for this round are available at {{ link_to_route('results.round.show', NULL, [$round, $round->access_code], ['target' => '_blank']) }} </p>
		</div>
	@endif

    @include('round.partial.single')
	<ul class="list-group">
		<li class="list-group-item">
			<h3>Divisions</h3>
            @foreach($round->divisions as $div)
            <p>Division: {{ $div->name }}</p>
            @endforeach

		</li>
        <li class="list-group-item">
            <h3>Judges</h3>
            @include('competition_division_judge.organizer.table')
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
