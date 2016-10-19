@extends('layouts.simple')

@section('breadcrumbs')

@endsection

@section('content-header')
	<h1>Users</h1>

	<ul class="actions-group">
		@can('create' , 'App\User')
		  <li>{{ link_to_route('admin.user.create', 'Add a user', [], ['class' => 'action']) }}</li>
		@endcan
	</ul>

@endsection

@section('content')

  @include('user.admin.table')

@endsection
