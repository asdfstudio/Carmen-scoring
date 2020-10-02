@extends('layouts.simple')

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.round.show',$competition,$round) !!}
@endsection

@section('content-header')
    <h1>Scoring Settings</h1>

    @can('update', $round)
        <ul class="actions-group">
            <li>{{ link_to_route('organizer.competition.round.edit','Edit scoring settings',[$competition, $round],['class' => 'action']) }}</li>
            <li>{{ link_to_route('organizer.competition.round.award.settings.edit','Edit Award Settings',[$competition, $round],['class' => 'action']) }}</li>
        </ul>
    @endcan

@endsection

@section('content')
    @can('viewResults', $round)
        <div class="alert alert-info d-flex">
            <i class="fa fa-commenting dg-fs-20 mr"></i>
            <p>Results for this round are available at {{ link_to_route('results.round.show', NULL, [$round, $round->access_code], ['target' => '_blank']) }} </p>
        </div>
    @endcan

    @include('round.partial.single')

@endsection
