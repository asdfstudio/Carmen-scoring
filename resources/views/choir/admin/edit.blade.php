@extends('layouts.simple')


@section('content-header')
  <h1>Edit ensemble</h1>

	{{ link_to_route('admin.choir.index', 'Back to ensembles', [], ['class' => 'action'])}}
@endsection

@section('content')

		{!! form($form) !!}

@endsection
