@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.judge.edit', $judge) !!}

		<h1>Edit Judge</h1>

    {!! form($form) !!}


@endsection
