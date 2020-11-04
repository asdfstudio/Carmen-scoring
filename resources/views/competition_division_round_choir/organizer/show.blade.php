@extends('layouts.simple')

@section('content-header')

	<h1>{{ $choir->full_name }}</h1>

	<ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.round.scores.show', 'Back to all choirs', [$competition, $round], ['class' => 'action'])}}
		</li>
	</ul>
@endsection

@section('content')

	<h2>Penalties</h2>

	<!-- The organizers will need to assign/remove penalties after scores have been completed, but never after scores have been sent. -->
	@if($division->status_slug() != 'finalized')
		{{ link_to_route('organizer.competition.division.penalty.choir.assign', 'Assign / Remove Penalties', [$competition->id, $division->id, $choir->id, 'redirect=choir_score'], ['class' => 'action'])}}
	@endif

	<hr>

	@include('penalty.organizer.list', ['penalties' => $choir->penalties])

	@if($competition->organization->is_premium == 1)
	<h2>Upload Comments</h2>

	@include('recordings.list', ['competition' => $competition,'division'=>$division,'round'=> $round,'choir' => $choir , 'judgeList' => $judgeList])

	<hr>
	@endif
	<h2>Scores</h2>

  @include('scores.organizer.choir_raw',['division' => $division])

@endsection
