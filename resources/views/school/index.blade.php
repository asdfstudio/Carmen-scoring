@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('admin.school.index') !!}

  @include('school.table')

@endsection
