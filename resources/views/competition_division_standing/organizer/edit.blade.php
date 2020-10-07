@extends('layouts.simple')

@section('content-header')


	@if($standing->caption_id == NULL)
		<h1>Edit Overall Standings</h1>
	@else
		<h1>Edit {{ $standing->caption->name }} Standings</h1>
	@endif

	<ul class="actions-group">
			<li>
				{{ link_to_route('organizer.competition.division.show', 'Back to Division', [$division->competition,$division], ['class' => 'action']) }}
			</li>

      <li>
				{{ link_to_route('organizer.competition.division.standing.show', 'Back to Standings', [$division->competition, $division], ['class' => 'action']) }}
			</li>
	</ul>
@endsection

@section('content')

	<p>
		This page allows you to modify the final standings for this division. It's purpose is to allow for manually overriding aggregate scores. It should be used for consensus scoring.
	</p>

	@include('standing.edit', ['standing' => $standing])

@endsection
