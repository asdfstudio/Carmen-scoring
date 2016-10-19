@extends('layouts.simple')

@section('content-header')
  <h1>Choirs</h1>

  {{ link_to_route('admin.choir.create', 'Add a choir', [], ['class' => 'action']) }}
@endsection

@section('content')

  @include('choir.admin.list')

@endsection
