@extends('layouts.app')

@section('content')

	{!! Breadcrumbs::render('admin.choir.index') !!}
	
  @include('choir.table')

@endsection
