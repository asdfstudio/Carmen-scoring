@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.index') !!}
@endsection

@section('content-header')
	<h1>Competitions</h1>
	<ul class="actions-group">
    @if(Auth::user()->isAdmin())
    <li class="switch-action">
        <span>Audience vote on/off&nbsp;&nbsp;&nbsp;</span>
        <label class="switch" >
          <input type="checkbox"
                 data-organization="{{$organization->id}}"
                 id="organization-vote" {{$organization->vote_setting?'checked':''}}
          >
          <span class="slider round"></span>
        </label>
      &nbsp;&nbsp;&nbsp;
    </li>
    @endif
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
