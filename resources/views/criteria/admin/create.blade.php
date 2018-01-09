@extends('layouts.simple')

@section('content-header')
  <h1>Create a scoring criterion</h1>

	{{ link_to_route('admin.criteria.index', 'Back to criteria', [], ['class' => 'action'])}}

@endsection

@section('content')
		{!! form($form) !!}
@endsection
