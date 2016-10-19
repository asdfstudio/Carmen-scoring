@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.user.index') !!}
@endsection

@section('content-header')
	<h1>Users</h1>

	<ul class="actions-group">
		@can('create' , 'App\User')
		  <li>{{ link_to_route('organizer.user.create', 'Add a user', NULL, ['class' => 'action']) }}</li>
		@endcan
	</ul>

@endsection

@section('content')



	<p>
		This page lists all of the users that are authorized to log in to your organization. Admin users have more rights to create, edit and delete items in your organization. Standard users can view items but have restricted access.
	</p>



  @include('user.organizer.table')

@endsection
