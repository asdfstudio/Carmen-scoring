@extends('layouts.public_results')

@section('content-header')

@endsection

@section('content')

	<h2>{{ $round->name}}</h2>

	<div class="alert alert-info">
		<h4>Viewing Notes</h4>
		<p>Click on a choir or judge to view their score details.</p>
	</div>

				<ul class="list-group horizontal">
					<li class="list-group-item">
						<?php $active = $division->scoringMethod->slug == 'ranked' ? 'active division-scoring-method' : false; ?>
						<a class="score-view-toggle {{ $active }}" href="#rankings" data-score-view="rank">Rankings</a>

						@if($active)
							<span>(division scoring method)</span>
						@endif
					</li>
					<li class="list-group-item">
						<?php $active = $division->scoringMethod->slug == 'raw' ? 'active division-scoring-method' : false; ?>
						<a class="score-view-toggle {{ $active }}" href="#weighted" data-score-view="weighted">Weighted</a>

						@if($active)
							<span>(division scoring method, {{ $division->captionWeighting->name }})</span>
						@else
							<span>({{ $division->captionWeighting->name }})</span>
						@endif

					</li>
					<li class="list-group-item">
						<a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
					</li>
				</ul>

				@include('scores.public.composite',['choirs' => $division->choirs, 'judges' => $division->judges, 'scoreboard' => $scoreboard])
			</div>

@endsection
