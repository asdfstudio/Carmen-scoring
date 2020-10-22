@extends('layouts.simple')

@php $include_division_navigation_bar = TRUE @endphp

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

    <h2>Choose a Choir</h2>

    <ul class="list-group">
      @foreach($division->choirs as $choir)
        <li class="list-group-item">
          {{ link_to_route('organizer.competition.division.penalty.choir.assign', $choir->full_name, [$division->competition, $division, $choir]) }}
        </li>
      @endforeach
    </ul>

@endsection
