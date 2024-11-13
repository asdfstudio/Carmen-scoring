@extends('layouts.public_results')

@section('content-header')

@endsection

@section('content')

  {!! Breadcrumbs::render('results.competition.show-public', $competition) !!}

  <h2>{{ $competition->name }} Results By Class</h2>

  @if($competition->divisions->count() == 0)
    <p>There are currently no classes with published results. Please check back again shortly.</p>
  @endif


  @if($competition->divisions->count() > 0)
    <ul class="list-group">
      @foreach($competition->divisions as $div)
        <li class="list-group-item">{{ link_to_route('results.division.show-public', $div->name, [$div])}}</li>

      @endforeach
    </ul>
  @endif

  <h3>Solo Classes</h3>

  <p>Please use the results link provided by the competition organizer to view solo class results</p>


@endsection
