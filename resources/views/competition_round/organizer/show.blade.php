@extends('layouts.simple')

@php $include_round_navigation_bar = TRUE @endphp

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.round.show',$competition, $round) !!}
@endsection

@section('content-header')
    <h1>{{ $round->name }}</h1>
@endsection

@section('content')
    <div class="clearfix"></div>
    <ul class="list-group">
        <h3>Scoring Settings</h3>

        @include('round.partial.single')
    </ul>
    <ul class="list-group">
        <div>
            <h3 style="width: 49%;display: inline-block">Divisions</h3>
            <div style="width: 50%;display: inline-block;text-align: right;padding-bottom: 10px">{{ link_to_route('organizer.competition.division.create','Create your division',[$competition], ['class' => 'action']) }}</div>
        </div>
        @foreach($round->divisions as $div)
            <li class="division list-group-item">
                <span class="name">{{ link_to_route('organizer.competition.division.show', $div->name, [$div->competition,$div]) }}</span>
                <span class="label status {{ $div->status_slug }} pull-right">{{ $div->status }}</span>
            </li>
        @endforeach
    </ul>
    <ul class="list-group">
        <h3>Judges</h3>
        @include('competition_division_judge.organizer.list',['judges' => $round->judges])
    </ul>

@endsection
