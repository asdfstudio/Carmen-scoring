@extends('layouts.simple')


@section('content-header')
	<h1>{{ $soloDivision->name }}</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('judge.competition.solo-division.show','Back to Solo Division',[$competition, $soloDivision],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

		<h2>{{ $performer->name }}, {{ $performer->choir->full_name }}</h2>

    @if ($soloDivision->status_slug == 'active')
      @include('performer.judge.score-form')
    @else
      @include('performer.judge.view-score')
    @endif


@endsection
