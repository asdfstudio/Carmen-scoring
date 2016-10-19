@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.show',$competition) !!}
@endsection

@section('competition_sidebar')
  @parent
@endsection

@section('content')



		<h1>Competition Details</h1>

    @if(!$competition->is_archived)
      {{ link_to_route('organizer.competition.edit', 'Edit Competition', [$competition]) }}
    @else
      Locked
    @endif

		{!! form($activateScoringForm) !!}

		{!! form($deactivateScoringForm) !!}

    {!! form($completeScoringForm) !!}

    <h3>{{ link_to_route('organizer.competition.division.index','Divisions',[$competition]) }}</h3>

    {{ link_to_route('organizer.competition.division.create','Add a division',[$competition],['class' => 'btn btn-primary']) }}

    @include('division.organizer.list',['divisions' => $competition->divisions])

@endsection
