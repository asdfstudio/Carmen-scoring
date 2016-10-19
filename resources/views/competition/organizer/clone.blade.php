@extends('layouts.app')

@section('content')

    {!! Breadcrumbs::render('organizer.competition.clone',$competition) !!}

    <h1>Clone a competition</h1>

    <ul>
      <li>Source competition: {{ $competition->name }}</li>
    </ul>


		{!! form($form) !!}

@endsection
