@extends('layouts.simple')

@section('breadcrumbs')

@endsection

@section('division_navigation_bar')

@endsection

@section('content')

  <h3>What do you want to do?</h3>

  <ul class="actions-group">
    <li>
      {{ link_to_route('judge.competition.division.scoring','Enter Scoring Mode', [$competition, $division], ['class' => 'action']) }}
    </li>
    <li>
      {{ link_to_route('judge.competition.division.details','View Division Details', [$competition, $division], ['class' => 'action']) }}
    </li>
  </ul>


@endsection
