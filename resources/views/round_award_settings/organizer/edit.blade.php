@extends('layouts.simple')

@php $include_round_navigation_bar = TRUE @endphp

@section('breadcrumbs')

@endsection

@section('content-header')
    <h1>Award Settings</h1>

    @can('update', $round)
        <ul class="actions-group">
            <li>{{ link_to_route('organizer.competition.round.award.index','Back to awards', [$round->competition->id, $round->id], ['class' => 'action']) }}</li>
        </ul>
    @endcan

@endsection

@section('content')

    {!! form($form) !!}

@endsection
