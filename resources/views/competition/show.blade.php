@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.competition.show', $competition) !!}

		<h1>Competition Details</h1>

		<h2>{{ $competition->name }} from {{ $competition->organization->name }} - # {{ $competition->id }}</h2>

    @if($competition->place)
    	<h3>Location Details</h3>
    	@include('place.show',['place' => $competition->place])
    @endif

@endsection
