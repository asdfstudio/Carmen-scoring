@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.organization.edit', $organization) !!}
@endsection

@section('content-header')
	<h1>Edit Organization Details</h1>

	{{ link_to_route('organizer.organization.show','Back to organization', [], ['class' => 'action']) }}
@endsection

@section('content')





		{!! form($form) !!}

@endsection
