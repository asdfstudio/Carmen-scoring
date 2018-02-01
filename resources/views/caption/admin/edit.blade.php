@extends('layouts.simple')

@section('content-header')
  <h1>Edit caption</h1>

	{{ link_to_route('admin.caption.index', 'Back to captions', [], ['class' => 'action'])}}
@endsection

@section('content')

		{!! form($form) !!}

    @include('caption.admin.color-chart')

@endsection
