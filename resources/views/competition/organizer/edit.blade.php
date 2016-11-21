@extends('layouts.simple')

@section('title')
  Edit {{ $competition->name }} | @parent
@endsection

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.edit', $competition) !!}
@endsection

@section('content')

		<h1>Edit competition</h1>

		{!! form($form) !!}

@endsection
