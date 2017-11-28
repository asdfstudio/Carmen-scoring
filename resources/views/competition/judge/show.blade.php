@extends('layouts.simple')

@section('content-header')
  <h3>Divisions</h3>
@endsection

@section('content')

    @include('division.judge.list',['divisions' => $competition->divisions])

    {{ link_to_route('judge.competition.schedule.index', 'Show Schedules', [$competition]) }}

@endsection
