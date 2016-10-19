@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.user.create') !!}
@endsection

@section('content-header')
	<h1>Create a user</h1>

	{{ link_to_route('organizer.user.index', 'Back to users', [], ['class' => 'action']) }}
@endsection

@section('content')

		@include('alert/all')

		{!! form($form) !!}

@endsection
