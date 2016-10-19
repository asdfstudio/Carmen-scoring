@extends('layouts.simple')

@section('content')

		{!! Breadcrumbs::render('organizer.competition.edit', $competition) !!}

		<h1>Edit competition</h1>

		{!! form($form) !!}

@endsection
