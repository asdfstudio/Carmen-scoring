@extends('layouts.public_results')



@section('content')

  <h1>{{ $competition->name }}</h1>

  <h2>
		@if ($categoryName)
			{{ $categoryName }}
		@endif
		{{ $soloDivision->name }} Results
	</h2>

	<ul class="actions-group mv">
		<li>{{ link_to_route('results.solo-division.show','Overall results',[$soloDivision, $access_code],['class' => 'action']) }}</li>

    @if ($soloDivision->category_1)
			<li>{{ link_to_route('results.solo-division.show', $soloDivision->category_1 . ' results',[$soloDivision, $access_code, 'category' => 1],['class' => 'action']) }}</li>
		@endif

		@if ($soloDivision->category_2)
			<li>{{ link_to_route('results.solo-division.show', $soloDivision->category_2 . ' results',[$soloDivision, $access_code, 'category' => 2],['class' => 'action']) }}</li>
		@endif
      <li>{{ link_to_route('results.solo-division.show', 'Audience vote results',[$soloDivision, $access_code, 'view' => 'audience-vote'],['class' => 'action']) }}</li>
	</ul>

  @if ($soloDivision->performers->count() > 0)
    @include('performer.public.table', ['performers' => $soloDivision->performers, 'judges' => $soloDivision->judges])
  @endif

@endsection
