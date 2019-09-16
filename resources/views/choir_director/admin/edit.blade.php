@extends('layouts.simple')

@section('content-header')
  <h1>{{ $choir->full_name }}</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('admin.choir.show','Back to Choir', [$choir], ['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

  <h2>Edit Director</h2>

  {!! form($form) !!}

  <h2>Remove director from choir</h2>

  {!! form($deleteForm) !!}

@endsection
