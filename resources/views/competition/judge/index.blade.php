@extends('layouts.simple')

@section('content')



  <h1>Active and Upcoming Competitions</h1>

  @include('competition.judge.list')


  <h2>Archived Competitions</h2>
  @include('competition.judge.list',['competitions' => $archivedCompetitions])

@endsection
