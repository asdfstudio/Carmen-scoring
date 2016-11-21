@extends('layouts.simple')

@section('title')
  {{ $competition->name }} | @parent
@endsection

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.show',$competition) !!}
@endsection

@section('content-header')
  <h1>Competition Details</h1>



  @if(!$competition->is_archived)
    {{ link_to_route('organizer.competition.edit', 'Edit Competition', [$competition], ['class' => 'action']) }}
  @endif

@endsection



@section('content')

  <ul class="actions-group">
    <li>{!! form($activateScoringForm) !!}</li>
    <li>{!! form($deactivateScoringForm) !!}</li>
    <li>{!! form($completeScoringForm) !!}</li>
  </ul>

  <h3>Manage Divisions</h3>
  <p>Divisions are used to organize your competition and consist of choirs, judges, scoring settings and more. {{ link_to_route('organizer.competition.division.index','Manage your divisions',[$competition]) }}</p>

  @if($competition->divisions->count() > 0)

    @include('division.organizer.list',['divisions' => $competition->divisions])
    
  @else
    <p>{{ link_to_route('organizer.competition.division.create','Create your first division',[$competition]) }}</p>
  @endif


@endsection
