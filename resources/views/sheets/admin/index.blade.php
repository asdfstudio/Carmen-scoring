@extends('layouts.simple')

@section('content-header')
  <h1>Scoring Sheets</h1>

	{{ link_to_route('admin.sheet.create', 'Add a scoring sheet', [], ['class' => 'action']) }}
@endsection

@section('content')

  @include('sheets.admin.list')

@endsection
