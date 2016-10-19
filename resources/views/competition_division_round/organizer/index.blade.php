@extends('layouts.simple')

@section('breadcrumbs')
{!! Breadcrumbs::render('organizer.competition.division.round.index',$division->competition,$division) !!}
@endsection

@section('content-header')
	<h1>Manage Rounds</h1>

	<ul class="actions-group">
		@can('create',['App\Round',$division])
			<li>
				{{ link_to_route('organizer.competition.division.round.create','Add a round',[$division->competition,$division], ['class' => 'action']) }}
			</li>
		@endcan

	</ul>

@endsection

@section('content')

  @include('competition_division_round.organizer.list', ['rounds' => $division->rounds])

@endsection
