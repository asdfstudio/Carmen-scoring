@extends('layouts.simple')

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.round.show',$competition, $round) !!}
@endsection

@section('content-header')
    <h1>{{ $round->name }}</h1>

    <ul class="actions-group">
        @can('update', $round)
            <li> {{ link_to_route('organizer.competition.round.edit', 'Edit Scoring', [$competition,$round], ['class' => 'action']) }} </li>
            <li>{{ link_to_route('organizer.competition.round.board', 'Edit Judges', [$competition, $round], ['class' => 'action']) }}</li>
            <li>{{ link_to_route('organizer.competition.division.index', 'Manage Divisions', [$competition, $round], ['class' => 'action']) }}</li>
        @endcan
        @if($round->competition->organization->vote_setting)
            <li> {{ link_to_route('organizer.competition.round.audience.index', 'Manage Audience Voting', [$competition, $round], ['class' => 'action']) }}</li>
        @endif
        @can('showAll', $round)
            <li>{{ link_to_route('organizer.competition.round.scores.show', 'See Scores', [$competition, $round], ['class' => 'action']) }}</li>
            <li>{{ link_to_route('organizer.competition.round.index', 'Back to all Rounds', [$competition], ['class' => 'action']) }}</li>
        @endcan
    </ul>
@endsection

@section('content')
    <div class="clearfix"></div>
    <ul class="list-group">
        <h3>Scoring Settings</h3>

        @include('round.partial.single')
    </ul>
    <ul class="list-group">
        <h3>Divisions</h3>
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
