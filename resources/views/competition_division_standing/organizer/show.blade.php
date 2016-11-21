@extends('layouts.simple')

@section('content-header')
	<h1>Final Standings</h1>

	<ul class="actions-group">
    @can('show', $division)
      <li>
				{{ link_to_route('organizer.competition.division.show', 'Back to Division', [$division->competition,$division], ['class' => 'action']) }}
			</li>
    @endcan

    @can('update', $division->standing)
      <li>
				{{ link_to_route('organizer.competition.division.standing.edit', 'Modify Standings', [$division->competition, $division], ['class' => 'action']) }}
			</li>
    @endcan
	</ul>
@endsection

@section('content')

  @if($division->standing == false)
    <p>
      There are no final standings yet.
    </p>
  @endif

  @if($division->standing)
    @if($division->standing->is_consensus_scoring)
      <p class="alert alert-warning">
        Consensus scoring is used for this division.
      </p>
    @endif

  	@include('standing.list', ['standing' => $division->standing])

  @endif

@endsection
