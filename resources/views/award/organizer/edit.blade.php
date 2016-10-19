@extends('layouts.simple')

@section('content-header')
	<h1>Edit award</h1>

	<ul class="actions-group">
		@can('showAll','App\Award')
		  <li>{{ link_to_route('organizer.award.index', 'Back to awards', false, ['class' => 'action']) }}</li>
		@endif
	</ul>
@endsection

@section('content')

		{!! form($form) !!}

@endsection
