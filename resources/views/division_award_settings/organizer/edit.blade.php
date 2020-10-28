@extends('layouts.simple')

@php $include_division_navigation_bar = TRUE @endphp

@section('breadcrumbs')

@endsection

@section('content-header')
    <h1>Award Settings</h1>

    @can('update', $division)
        <ul class="actions-group">
            <li>{{ link_to_route('organizer.competition.division.award.index','Back to awards', [$division->competition->id, $division->id], ['class' => 'action']) }}</li>
        </ul>
    @endcan

@endsection

@section('content')

    {!! form($form) !!}

@endsection
