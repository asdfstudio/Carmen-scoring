@extends('layouts.simple')

@section('content-header')
  <h1>Captions</h1>

	{{ link_to_route('admin.caption.create', 'Add a caption', [], ['class' => 'action']) }}
@endsection

@section('content')

    @include('caption.admin.list')

@endsection
