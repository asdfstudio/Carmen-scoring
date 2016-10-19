@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.school.edit', $school) !!}

		<h1>Edit school</h1>

		{!! form($form) !!}

@endsection
