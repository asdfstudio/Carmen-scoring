@extends('layouts.app')

@section('content')

    {!! Breadcrumbs::render('organizer.competition.division.clone',$competition,$division) !!}

    <h1>Duplicate this division: {{ $division->name }}</h1>

    <ul>
      <li>Source: {{ $division->name }}</li>
    </ul>


		{!! form($form) !!}

@endsection
