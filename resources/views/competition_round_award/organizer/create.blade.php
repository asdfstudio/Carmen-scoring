@extends('layouts.simple')

@section('content-header')
  <h1>Create an award</h1>

  <ul class="actions-group">
    @can('showAll','App\Award')
			<li>
				{{ link_to_route('organizer.competition.round.award.index','Back to awards', [$round->competition->id, $round->id], ['class' => 'action']) }}
			</li>
		@endcan
  </ul>
@endsection

@section('content')

		{!! form($form) !!}

@endsection
