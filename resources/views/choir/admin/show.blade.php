@extends('layouts.simple')

@section('content-header')
  <h1>{{ $choir->full_name }}</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('admin.choir.index','Back to All Choirs', [], ['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

  <h2>Director(s)</h2>

  @include('person.partial.list', ['people' => $choir->directors])

  {{ link_to_route('admin.choir.director.create','Add a director', [$choir], ['class' => 'action']) }}

@endsection
