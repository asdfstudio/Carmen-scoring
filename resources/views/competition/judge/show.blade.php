@extends('layouts.simple')


@section('content')

    <h3>Group Divisions</h3>
    @if ($competition->divisions->count() > 0)
      @include('division.judge.list',['divisions' => $competition->divisions])
    @else
      <p>There are no divisions.</p>
    @endif


    <h3>Solo Divisions</h3>

    @if ($competition->soloDivisions->count() > 0)
      @include('solo-division.judge.list',['soloDivisions' => $competition->soloDivisions])
    @else
      <p>There are no solo divisions.</p>
    @endif

    {{ link_to_route('judge.competition.schedule.index', 'Show Schedules', [$competition]) }}

@endsection
