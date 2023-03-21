@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.choir.show', $choir) !!}

		<h1>Ensemble Details</h1>

		@include('choir.partial.single')

@endsection
