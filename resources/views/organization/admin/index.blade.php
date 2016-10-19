@extends('layouts.simple')

@section('content-header')
  <h1>Organizations</h1>

  {{ link_to_route('admin.organization.create', 'Add an organization', [] ,['class' => 'action']) }}
@endsection


@section('content')

  @include('organization.admin.list')

@endsection
