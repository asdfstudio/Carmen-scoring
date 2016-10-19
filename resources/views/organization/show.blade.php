@extends('layouts.simple')

@section('content')

		{!! Breadcrumbs::render('admin.organization.show', $organization) !!}

		<h1>Organization Details</h1>

		<h2>{{ $organization->name }} - # {{ $organization->id }}</h2>

    {{ link_to_route('admin.organization.edit','Edit organization', [$organization]) }}

    <h3>Organization Contacts</h3>

		{{ $organization->people }}

@endsection
