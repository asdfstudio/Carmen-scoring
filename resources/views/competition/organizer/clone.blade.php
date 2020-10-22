@extends('layouts.simple')

@section('content-header')
    <h1>Clone a competition</h1>
@endsection

@section('content')

    {!! Breadcrumbs::render('organizer.competition.clone',$competition) !!}
    <ul>
      <li>Source competition: {{ $competition->name }}</li>
    </ul>


		{!! form($form) !!}

@endsection
