@extends('layouts.simple')

@section('content-header')
  <h1>Add a round to this division</h1>

  <ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.division.round.index','Back to rounds',[$division->competition,$division], ['class' => 'action']) }}
		</li>
	</ul>
@endsection

@section('content')
		{!! form($form) !!}
@endsection
