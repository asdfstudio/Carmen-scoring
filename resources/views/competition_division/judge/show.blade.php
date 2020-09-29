@extends('layouts.simple')


@section('division_navigation_bar')
  @if (isset($division))
    <div class="division-navigation-bar body-width">
      <ul class="division-navigation tab-links">
        <!--<li>
          <a href="#overview">Overview</a>
        </li>-->
        <li>
          <a href="#scoring" class="active tab-link" data-tab-id="scoring">Settings</a>
        </li>
        <li>
          <a href="#choirs" class="tab-link" data-tab-id="choirs">Choirs</a>
        </li>

        <li>
          <a href="#audience_vote" class="tab-link" data-tab-id="audience_vote">Audience vote</a>
        </li>

        <li>
          <a href="#judges" class="tab-link" data-tab-id="judges">Judges</a>
        </li>
        <li>
          <a href="#rounds" class="tab-link" data-tab-id="rounds">Rounds</a>
        </li>

        <li>
          <a href="#penalties" class="tab-link" data-tab-id="penalties">Penalties</a>
        </li>
        <li>
          <a href="#" class="tab-link" data-tab-id="awards">Awards</a>
        </li>
        <li>
          <a href="#" class="tab-link" data-tab-id="standings">Final Standings</a>
        </li>
        <li class="scoring">
          <a href="{{ route('judge.competition.division.scoring', [$competition, $division]) }}">Enter Scoring Mode</a>
        </li>
      </ul>
    </div>
  @endif
@endsection

@section('content')



  <div class="row">

    <div data-tab-id="scoring" class="active tab-content col-xs-12 col-sm-12">
      <h2>Scoring Settings</h2>
      @include('division.partial.single')
    </div>

    <div data-tab-id="choirs" class="tab-content col-xs-12 col-sm-12">
      <h2>Choirs</h2>
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
    	@include('competition_division_judge.judge.list',['judges' => $division->judges])
    </div>

    <div data-tab-id="rounds" class="tab-content col-xs-12 col-sm-12">
      <h2>Rounds</h2>
      @include('competition_division_round.judge.list', ['rounds' => $division->rounds])
    </div>

    <div data-tab-id="penalties" class="tab-content col-xs-12 col-sm-12">
      <h2>Penalties</h2>
      @include('penalty.organizer.list', ['penalties' => $division->penalties])
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
