@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.competition.edit', $competition) !!}

		<h1>Edit competition</h1>

		{!! form($form) !!}

@endsection
