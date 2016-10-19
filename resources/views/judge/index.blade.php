@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('admin.judge.index') !!}

  @include('judge.table')

@endsection
