@extends('layouts.simple')

@section('content-header')
  <h1>Edit sheet</h1>

	{{ link_to_route('admin.sheet.index', 'Back to sheets', [], ['class' => 'action'])}}
@endsection

@section('content')

		{!! form($form) !!}

@endsection
