@extends('layouts.simple')

@section('content-header')
  <h1>Create a school</h1>

	{{ link_to_route('admin.school.index', 'Back to schools', [], ['class' => 'action'])}}

@endsection

@section('content')
		{!! form($form) !!}
@endsection
