@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.index') !!}
@endsection
<style type="text/css">

</style>
@section('content-header')
	<h1>Competitions</h1>
	<ul class="actions-group">
		@can('create','App\Competition')
			<li>{{ link_to_route('organizer.competition.create','Add a competition',NULL,['class' => 'action']) }}</li>
		@endcan
	</ul>

@endsection

@section('content')
	<p>
		Below you will find a list of your active and archived competitions. Active competitions are those that are upcoming or in-progress. Archived competitions are those that have been completed.
	</p>
  <h2>Active Competitions</h2>
  @include('competition.organizer.table')
  <h2>Archived Competitions</h2>
  @include('competition.organizer.table',['competitions' => $archivedCompetitions])

@endsection
