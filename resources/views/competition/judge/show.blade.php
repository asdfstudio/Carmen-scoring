@extends('layouts.simple')


@section('content')

    <h3>Rounds</h3>
    @if($competition->rounds->count() > 0)
      @include('round.judge.list',['rounds' => $competition->rounds])
  @else
      <p>You have no rounds to score</p>
  @endif

    <h3>Solo Divisions</h3>

    @if ($competition->soloDivisions->count() > 0)
      @include('solo-division.judge.list',['soloDivisions' => $competition->soloDivisions])
    @else
      <p>There are no solo divisions.</p>
    @endif

@endsection
