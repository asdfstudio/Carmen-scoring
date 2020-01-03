@extends('layouts.simple')

@section('content-header')

	<h1>{{ $choir->full_name }}</h1>

	<ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.division.round.show', 'Back to all choirs', [$competition->id, $division->id, $round->id], ['class' => 'action'])}}
		</li>
	</ul>
@endsection


@section('content')

	<h2>Penalties</h2>

	{{ link_to_route('organizer.competition.division.round.choir.penalty.assign', 'Assign / Remove Penalties', [$competition->id, $division->id, $round->id, $choir->id], ['class' => 'action'])}}

	<hr>

	@include('penalty.organizer.list', ['penalties' => $choir->penalties])

	@if($competition->organization->is_premium == 1)
	<h2>Upload Comments</h2>

	@include('recordings.list', ['choir_id' => $choir->id, 'judgeList' =>$judgeList, 'division_id'=>$division->id,'round_id'=> $round->id, 'judge_id' => $judge_id ])
	
	<hr>
	@endif
	<h2>Scores</h2>
	
  @include('scores.organizer.choir_raw',['division' => $division, 'judge' => $round->division->judges->first()])

@endsection
