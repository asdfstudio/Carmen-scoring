@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.choir.show', $choir) !!}

		<h1>Choir Details</h1>

		@include('choir.partial.single')

@endsection
