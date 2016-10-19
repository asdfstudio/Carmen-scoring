@extends('layouts.simple')


@section('content')

	<div class="choir-bar">
    <div class="heading">
      <span class="subheading">{{ $choir->school->name }}</span>
      {{ $choir->name }}
    </div>
    <div class="choir-actions">
      <ul class="actions-group">

      </ul>
    </div>
  </div>


	{{ link_to_route('organizer.competition.division.round.choir.penalty.assign', 'Assign / Remove Penalties', [$competition->id, $division->id, $round->id, $choir->id], ['class' => 'btn btn-primary'])}}

	@include('penalty.organizer.list', ['penalties' => $choir->penalties])

  @include('scores.organizer.choir_raw',['division' => $round->division, 'judge' => $round->division->judges->first()])

@endsection
