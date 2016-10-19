@extends('layouts.simple')

@section('content-header')
  <h1>Schools</h1>

	{{ link_to_route('admin.school.create', 'Add a school', [], ['class' => 'action']) }}
@endsection

@section('content')

  @include('school.admin.list')

@endsection
