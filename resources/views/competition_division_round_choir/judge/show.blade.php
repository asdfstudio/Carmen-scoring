@extends('layouts.simple')

@section('division_navigation_bar')

@endsection

@section('content')



  <div class="choir-bar">
    <div class="heading">
      <span class="subheading">{{ $choir->school->name }}</span>
      {{ $choir->name }}
    </div>
    <div class="choir-actions">
      <ul class="actions-group">
        <!--<li>
          <a href="#" class="action">Switch Choirs</a>
        </li>-->
        <li>
          {{ link_to_route('judge.round.scores.summary', 'All Choirs', [$competition, $division, $round], ['class' => 'action'])}}
        </li>
      </ul>
    </div>
  </div>

  @if($round->is_scoring_active AND $judge->id == Auth::user()->person_id)

  	@include('scores.forms.choir_raw_alt',['division' => $round->division, 'judge' => $round->division->judges->first()])

  @else

  	@include('scores.choir_judge_raw',['division' => $round->division])

  @endif

@endsection
