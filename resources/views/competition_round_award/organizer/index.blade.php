@extends('layouts.simple')

@php $include_round_navigation_bar = TRUE @endphp

@section('content-header')
    <h1>Awards</h1>
    <ul class="actions-group">
        @can('update', $round)
            <li>
            {{ link_to_route('organizer.competition.round.award.settings.edit', 'Edit point based awards', [$round->competition->id, $round], ['class' => 'action']) }}
            </li>
        @endcan
        @can('createForRound', ['App\Award', $round])
            <li>
                {{ link_to_route('organizer.competition.round.award.create','Create round award', [$round->competition->id, $round->id], ['class' => 'action']) }}
            </li>
        @endcan

        @can('manage' , ['App\Award', $round])
            <li>
                {{ link_to_route('organizer.competition.round.award.manage','Manage type awards', [$round->competition->id, $round->id], ['class' => 'action']) }}
            </li>
        @endcan

        @can('assign' , ['App\Award', $round])
            <li>
                {{ link_to_route('organizer.competition.round.award.assign', 'Assign awards', [$round->competition->id, $round->id], ['class' => 'action']) }}
            </li>
        @endcan
    </ul>

@endsection

@section('content')

    <h2>Caption Specific Awards</h2>
    @include('round_award_settings.organizer.list', ['awardSettings' => $round->awardSettings])

    <h2>Other Awards</h2>
    @include('award.organizer.round_awards_list')

@endsection
