@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.choir.edit', $choir) !!}

		<h1>Edit ensemble</h1>

		{!! form($form) !!}

@endsection
