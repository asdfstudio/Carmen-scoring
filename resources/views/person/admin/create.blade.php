@extends('layouts.simple')

@section('breadcrumbs')

@endsection

@section('content-header')
	<h1>Create a Person</h1>

	{{ link_to_route('admin.user.index', 'Back to users', [], ['class' => 'action']) }}
@endsection

@section('content')

		{!! form($form) !!}

@endsection

@section('body-footer')
  <script src="/js/user-person-form.js"></script>
@endsection
