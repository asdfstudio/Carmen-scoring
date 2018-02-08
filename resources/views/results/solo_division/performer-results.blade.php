@extends('layouts.public_results')



@section('content')

  <h1>{{ $competition->name }}, {{ $soloDivision->name }}</h1>

  <ul class="actions-group mv">
		<li>{{ link_to_route('results.solo-division.show','Back to Overall results',[$soloDivision, $access_code],['class' => 'action']) }}</li>
	</ul>

  <h2>{{ $performer->name }}, {{ $performer->choir->full_name }}</h2>

  @include('performer.organizer.view-scores')



@endsection
