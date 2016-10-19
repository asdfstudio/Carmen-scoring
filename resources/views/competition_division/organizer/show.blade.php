@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.show',$competition,$division) !!}
@endsection
 
@section('content')


	{!! form($activateScoringForm) !!}

	{!! form($deactivateScoringForm) !!}

	{!! form($completeScoringForm) !!}

  {{ link_to_route('organizer.competition.division.edit', 'Edit Division', [$competition,$division],['class' => 'btn btn-primary']) }}

	{{ link_to_route('organizer.competition.division.clone', 'Clone Division', [$competition,$division],['class' => 'btn btn-primary']) }}

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
