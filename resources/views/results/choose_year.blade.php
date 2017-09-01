@extends('layouts.public_results')

@section('breadcrumbs')
	{!! Breadcrumbs::render('results.index') !!}
@endsection

@section('content')

  <h2>Competition Results - Select Year</h2>

  <ul class="list-group">
    @foreach($years as $year)
      <li class="list-group-item">{{ link_to_route('results.year', $year, [$year])}}</li>

    @endforeach
  </ul>


@endsection
