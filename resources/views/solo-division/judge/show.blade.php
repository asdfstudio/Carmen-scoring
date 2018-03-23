@extends('layouts.simple')


@section('content-header')
	<h1>{{ $soloDivision->name }}</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('judge.competition.show','Back to Competition',[$competition],['class' => 'action']) }}</li>
		<li>{{ link_to_route('judge.competition.solo-division.results','View Results', [$competition, $soloDivision], ['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')
		<ul class="list-group">
      <li class="list-group-item">Status: {{ $soloDivision->status }}</li>
      <li class="list-group-item">Max Performers: {{ $soloDivision->max_performers }}</li>
      <li class="list-group-item">Scoring Sheet: {{ $soloDivision->sheet->name }}</li>
			<li class="list-group-item">Category #1 Name: {{ $soloDivision->category_1 }}</li>
			<li class="list-group-item">Category #2 Name: {{ $soloDivision->category_2 }}</li>
    </ul>

    <h2>Judges</h2>

    @if ($soloDivision->judges->count() > 0)
      <ul class="list-group">
        @foreach ($soloDivision->judges as $judge)
          <li class="list-group-item">{{ $judge->full_name }}</li>
        @endforeach
      </ul>
    @else
      <p>There are no judges set up for this solo division.</p>
    @endif



    <h2>Performers</h2>

    @if ($soloDivision->performers->count() > 0)
      @include('performer.judge.table', ['performers' => $soloDivision->performers])
    @else
      <p>There are no performers set up for this solo division.</p>
    @endif

@endsection
