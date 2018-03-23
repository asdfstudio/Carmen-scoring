@extends('layouts.simple')


@section('content-header')
	<h1>
		@if (isset($categoryName))
			{{ $categoryName }}
		@endif
		{{ $soloDivision->name }} Results
	</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('judge.competition.solo-division.show','Back to Solo Division', [$competition, $soloDivision], ['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

	<ul class="actions-group mv">
		<li>{{ link_to_route('judge.competition.solo-division.results','Overall results',[$competition, $soloDivision],['class' => 'action']) }}</li>

		@if ($soloDivision->category_1)
			<li>{{ link_to_route('judge.competition.solo-division.results', $soloDivision->category_1 . ' results',[$competition, $soloDivision, 'category' => 1],['class' => 'action']) }}</li>
		@endif

		@if ($soloDivision->category_2)
			<li>{{ link_to_route('judge.competition.solo-division.results', $soloDivision->category_2 . ' results',[$competition, $soloDivision, 'category' => 2],['class' => 'action']) }}</li>
		@endif
	</ul>

  @if ($soloDivision->performers->count() > 0)
    @include('performer.judge.results-table', ['performers' => $soloDivision->performers, 'judges' => $soloDivision->judges])
  @endif

@endsection
