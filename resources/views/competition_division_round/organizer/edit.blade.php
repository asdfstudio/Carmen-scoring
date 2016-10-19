@extends('layouts.simple')

@section('content-header')
  <h1>Edit round</h1>

  <ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.division.round.index','Back to rounds',[$division->competition,$division], ['class' => 'action']) }}
		</li>
	</ul>
@endsection

@section('content')

		{!! form($form) !!}

    @can('destroy',$round)
      <h2>Remove round from this division</h2>

      {!! form($deleteForm) !!}
    @endcan

@endsection
