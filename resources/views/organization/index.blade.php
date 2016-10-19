@extends('layouts.simple')

@section('content')

	{!! Breadcrumbs::render('admin.organization.index') !!}

  @include('organization.table')

@endsection
