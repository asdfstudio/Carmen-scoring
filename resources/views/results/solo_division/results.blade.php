@extends('layouts.public_results')



@section('content')

  <h1>{{ $competition->name }}</h1>

  <h2>
		@if ($genderName)
			{{ $genderName }}
		@endif
		{{ $soloDivision->name }} Results
	</h2>

	<ul class="actions-group mv">
		<li>{{ link_to_route('results.solo-division.show','Overall results',[$soloDivision, $access_code],['class' => 'action']) }}</li>
		<li>{{ link_to_route('results.solo-division.show','Female results',[$soloDivision, $access_code, 'F'],['class' => 'action']) }}</li>
		<li>{{ link_to_route('results.solo-division.show','Male results',[$soloDivision, $access_code, 'M'],['class' => 'action']) }}</li>
	</ul>

  @if ($soloDivision->performers->count() > 0)
    @include('performer.public.table', ['performers' => $soloDivision->performers, 'judges' => $soloDivision->judges])
  @endif

@endsection
