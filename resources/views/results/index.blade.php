@extends('layouts.public_results')

@section('breadcrumbs')
	{!! Breadcrumbs::render('results.year', $year) !!}
@endsection

@section('content')

  <h2>Competition Results - {{ $year }}</h2>

  <ul class="list-group">
    @foreach($competitions as $comp)
      <li class="list-group-item">{{ link_to_route('results.competition.show-public', $comp->name, [$comp])}}</li>

    @endforeach
  </ul>


@endsection
