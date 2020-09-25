@extends('layouts.simple')

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.round.index',$competition) !!}
@endsection

@section('content-header')
	<h1>Manage Rounds</h1>

	<ul class="actions-group">
	@can('createRound', [$competition])
		{{ link_to_route('organizer.competition.round.create', 'Add a Round', [$competition], ['class' => 'action']) }}
	@endcan
	</ul>
@endsection

@section('content')

  @include('competition_round.organizer.table',['rounds' => $competition->rounds])

@endsection
