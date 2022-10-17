@extends('layouts.simple')

@section('content-header')
  <h1>Ensembles</h1>

  {{ link_to_route('admin.choir.create', 'Add an ensemble', [], ['class' => 'action']) }}
@endsection

@section('content')

  @include('choir.admin.list')

@endsection
