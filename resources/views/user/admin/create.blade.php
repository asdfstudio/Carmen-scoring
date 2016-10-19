@extends('layouts.simple')

@section('breadcrumbs')

@endsection

@section('content-header')
	<h1>Create a user</h1>

	{{ link_to_route('admin.user.index', 'Back to users', [], ['class' => 'action']) }}
@endsection

@section('content')

		{!! form($form) !!}

@endsection
