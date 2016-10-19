@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.organization.show') !!}
@endsection

@section('content-header')
	<h1>Organization Details</h1>

	@can('update',$organization)
		{{ link_to_route('organizer.organization.edit','Edit organization', [], ['class' => 'action']) }}
	@endcan
@endsection

@section('content')



		<h2>{{ $organization->name }}</h2>

		<h3>Location</h3>

		@include('place.show', ['place' => $organization->place])



@endsection
