@extends('layouts.simple')

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.round.index',$competition) !!}
@endsection

@section('content-header')
	<h1>Manage Types</h1>

	@can('create', App\Round::class)
		{{ link_to_route('organizer.competition.round.create', 'Add a Type', [$competition], ['class' => 'action']) }}
	@endcan
@endsection

@section('content')

  @include('competition_round.organizer.table',['rounds' => $competition->rounds])

@endsection
