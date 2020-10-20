@extends('layouts.simple')

@php $include_division_navigation_bar = TRUE @endphp

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.division.show',$competition,$division) !!}
@endsection

@section('content')


    <ul class="actions-group mv">
        @can('activateScoring', $division)
            <li>{!! form($activateScoringForm) !!}</li>
        @endcan

        @can('reactivateScoring', $division)
            <li>
                {!! form($reactivateScoringForm) !!}
            </li>
        @endcan

        @can('deactivateScoring', $division)
            <li>
                {!! form($deactivateScoringForm) !!}
            </li>
        @endcan

        @can('completeScoring', $division)
            <li>{!! form($completeScoringForm) !!}</li>
        @endcan

    </ul>

    <div class="clearfix"></div>

    @if ($division->isMissingScores())
        <p class="alert alert-warning">This division is currently missing scores. Do not complete the scoring until you have received scores from all judges.</p>
    @endif

    @if($division->status_slug() == 'finalized')
        <div class="alert alert-info">
            <p>Results for this division are available at {{ link_to_route('results.division.show', NULL, [$division, $division->access_code], ['target' => '_blank']) }} </p>
        </div>
    @endif

    <ul class="list-group">
        <li class="list-group-item">
            <h3>Division in Round {{ $division->round->name }}</h3>
            <h4>Division Rating Systems</h4>
            @foreach ($division->rating_system as $rating)
                <p>{{ $rating['name'] }}: {{ $rating['min_score'] }}%</p>
            @endforeach
        </li>
        <li class="list-group-item">
            <h3>Choirs</h3>
            @include('competition_division_choir.organizer.table')
        </li>
    </ul>

@php
    if($division->round->scoring_method_id === 3 || $division->round->scoring_method_id === 4){
        $rankings_tab_name = "Condorcet";
        $rankings_class = "condorcet";
        $is_condorcet = true;
        $show_borda = true;
    } else {
        $rankings_tab_name = "Rankings";
        $rankings_class = "rank";
        $is_condorcet = false;
        $show_borda = false;
    }
@endphp

@parent

@if (!$division->isMissingScores())
    <p class="alert alert-warning">This round is currently missing scores. Do not complete the scoring until you have received scores from all judges.</p>

    {{-- Raw Scoring, 50/50 --}}
    @if ($division->round->scoring_method_id === 1 && $division->round->caption_weighting_id === 2)
        <ul class="list-group horizontal">
            <li class="list-group-item">
                <a class="score-view-toggle active" href="#raw" data-score-view="raw">Raw</a>
            </li>
        </ul>
    @endif

    {{-- Raw Scoring, 60/40 --}}
    @if ($division->round->scoring_method_id === 1 && $division->round->caption_weighting_id === 1)
        <ul class="list-group horizontal">
            <li class="list-group-item">
                <a class="score-view-toggle active division-scoring-method" href="#weighted" data-score-view="weighted">Weighted</a>
                <span>(division scoring method, {{ $division->round->captionWeighting->name }})</span>
            </li>
            <li class="list-group-item">
                <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
            </li>
        </ul>
    @endif

    {{-- Ranked Scoring, 50/50 --}}
    @if ($division->round->scoring_method_id > 1 && $division->round->caption_weighting_id === 2)
        <ul class="list-group horizontal">
            <li class="list-group-item">
                <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="{{ $rankings_class }}">{{ $rankings_tab_name }}</a>
                <span>(division scoring method)</span>
            </li>
            @if ($show_borda)
                <li class="list-group-item">
                    <a class="score-view-toggle" href="#rankings" data-score-view="rank">Borda Count</a>
                </li>
            @endif
            <li class="list-group-item">
                <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
            </li>
        </ul>
    @endif

    {{-- Ranked Scoring, 60/40 --}}
    @if ($division->round->scoring_method_id > 1 && $division->round->caption_weighting_id === 1)
        <ul class="list-group horizontal">
            <li class="list-group-item">
                <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="{{ $rankings_class }}">{{ $rankings_tab_name }}</a>
                <span>(division scoring method)</span>
            </li>
            @if ($show_borda)
                <li class="list-group-item">
                    <a class="score-view-toggle" href="#rankings" data-score-view="rank">Borda Count</a>
                </li>
            @endif
            <li class="list-group-item">
                <a class="score-view-toggle" href="#weighted" data-score-view="weighted">Weighted</a>
                <span>({{ $division->round->captionWeighting->name }})</span>
            </li>
            <li class="list-group-item">
                <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
            </li>
        </ul>
    @endif

        {{-- Condorcet methods have an extra table that is formatted a little differently to show rankings. --}}
        @if($is_condorcet)
            @include('scores.organizer.ranked_condorcet',['choirs' => $choirs, 'judges' => $division->judges])
        @endif

    @include('scores.organizer.composite',['choirs' => $choirs, 'judges' => $judges])

@endif
  </div>

@endsection
