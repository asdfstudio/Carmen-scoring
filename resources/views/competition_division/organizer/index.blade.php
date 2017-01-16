@extends('layouts.simple')

@section('breadcrumb')
	{!! Breadcrumbs::render('organizer.competition.division.index',$competition) !!}
@endsection

@section('content-header')
	<h1>Manage Divisions</h1>

	@can('createDivision', [$competition])
		{{ link_to_route('organizer.competition.division.create', 'Add a division', [$competition], ['class' => 'action']) }}
	@endcan
@endsection

@section('content')

  @include('competition_division.organizer.table',['divisions' => $competition->divisions])

@endsection
