@extends('layouts.simple')

@section('content-header')
	<h1>Edit Final Standings</h1>

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

	@include('standing.edit', ['standing' => $division->standing])

@endsection
