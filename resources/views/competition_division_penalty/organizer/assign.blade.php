@extends('layouts.simple')

@section('content-header')
	<h1>Assign a Penalty</h1>

	<ul class="actions-group">
		@can('create' , 'App\Penalty')
			<li>
				{{ link_to_route('organizer.competition.division.penalty.index','Back to penalties', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>
@endsection

@section('content')

  @if($round)
    <h2>Choose a Choir</h2>

    <ul class="list-group">
      @foreach($round->choirs as $choir)
        <li class="list-group-item">
          {{ link_to_route('organizer.competition.division.round.choir.penalty.assign', $choir->full_name, [$division->competition, $division, $round, $choir]) }}
        </li>
      @endforeach
    </ul>
  @endif

  @if($round == false)
    <h2>Choose a Round</h2>

    <ul class="list-group">
      @foreach($division->rounds as $round)
        <li class="list-group-item">
          {{ link_to_route('organizer.competition.division.penalty.assign', $round->name, [$division->competition, $division, 'round' => $round]) }}
        </li>
      @endforeach
    </ul>
  @endif



@endsection
