@extends('layouts.pdf')

@section('content')
  <h1>{{ $division->competition->name }}</h1>
  <h2>{{ $division->name }}</h2>

  <h3>Awards</h3>

  <h4>Individual Awards</h4>
  @include('award.public_table', ['awards' => $division->awards])

  <h4>Overall & Caption Awards</h4>
  @php $xDivisionStandings = $division->standings;@endphp
  @include('standing.public_awards_table', ['standings' => $xDivisionStandings, 'awardSettings' => $division->awardSettings])

  <h3>Standings</h3>
  @include('standing.public_table', ['standings' => $division->standings])

  <h3>Rounds</h3>

  <h4>Round</h4>

  <h5>Rankings</h5>

  <h5>Weighted Scores</h5>

  <h5>Raw Scores</h5>

  <h5>Round + Choirs</h5>
@endsection
