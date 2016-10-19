@extends('layouts.simple')


@section('content-header')
  <h1>Edit choir</h1>

	{{ link_to_route('admin.choir.index', 'Back to choirs', [], ['class' => 'action'])}}
@endsection

@section('content')

		{!! form($form) !!}

@endsection
