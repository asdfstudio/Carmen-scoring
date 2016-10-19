@extends('layouts.simple')

@section('content-header')
  <h1>Edit a penalty</h1>

  <ul class="actions-group">
		<li>
			{{ link_to_route('organizer.penalty.index', 'Back to penalties', [], ['class' => 'action']) }}
		</li>
	</ul>
@endsection

@section('content')

		{!! form($form) !!}

@endsection
