@extends('layouts.simple')

@php $include_judge_navigation_bar = TRUE @endphp

@section('content')



  <div class="row">

    <div data-tab-id="scoring" class="active tab-content col-xs-12 col-sm-12">
      <h2>Scoring Settings</h2>
      @include('round.partial.single')
    </div>

    <div data-tab-id="choirs" class="tab-content col-xs-12 col-sm-12">
      <h2>Ensembles</h2>
      @include('competition_division_choir.judge.list',['choirs' => $division->choirs])
    </div>

    @if($division->competition->organization->vote_setting)
    <div data-tab-id="audience_vote" class="tab-content col-xs-12 col-sm-12">
      <h2>Audience vote</h2>
      @include('competition_division_audience_vote.judge.list',['audience_vote' => $division->audience_vote])
    </div>
    @endif

    <div data-tab-id="judges" class="tab-content col-xs-12 col-sm-12">
    	<h2>Judges</h2>
        @include('competition_division_judge.judge.list',['judges' => $division->round->judges])
    </div>

    <div data-tab-id="rounds" class="tab-content col-xs-12 col-sm-12">
      <h2>Rounds</h2>
      @include('competition_round.judge.list', ['rounds' => $division->round])
    </div>

    <div data-tab-id="penalties" class="tab-content col-xs-12 col-sm-12">
      <h2>Penalties</h2>
      @include('penalty.organizer.list', ['penalties' => $round->penalties])
    </div>

    <div data-tab-id="awards" class="tab-content col-xs-12 col-sm-12">
    	<h2>Awards</h2>
    	@include('award.organizer.list', ['awards' => $division->awards])
    </div>

    <div data-tab-id="standings" class="tab-content col-xs-12 col-sm-12">
    	<h2>Finals Standings</h2>
    	@include('competition_division_standing.judge.show')
    </div>


  </div>



@endsection
