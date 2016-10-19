@extends('layouts.simple')

@section('content')

		{!! Breadcrumbs::render('organizer.user.show', $user) !!}

		<h1>User Details</h1>

		<h2>{{ $user->email }} - # {{ $user->id }}</h2>


    <h3>Delete User</h3>

    {!! form($deleteUserForm) !!}

@endsection
