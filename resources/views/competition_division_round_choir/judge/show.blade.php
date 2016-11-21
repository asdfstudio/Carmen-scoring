@extends('layouts.simple')

@section('division_navigation_bar')

@endsection

@section('content-header')
  <h1>{{ $choir->full_name }}</h1>

  <ul class="actions-group">
    <li>
      {{ link_to_route('judge.round.scores.summary', 'All Choirs', [$competition, $division, $round], ['class' => 'action'])}}
    </li>
  </ul>
@endsection


@section('content')


  @if($round->is_scoring_active AND $judge->id == Auth::user()->person_id)

  	@include('scores.forms.choir_raw_alt',['division' => $round->division, 'judge' => $round->division->judges->first()])

  @else

  	@include('scores.choir_judge_raw',['division' => $round->division])

  @endif

@endsection
