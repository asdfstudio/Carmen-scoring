@extends('layouts.simple')

@section('content-header')
  <h1>Add a round to this division</h1>

  <ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.round.index','Back to competition',[$round->competition], ['class' => 'action']) }}
		</li>
	</ul>
@endsection

@section('content')
		{!! form($form) !!}
@endsection
