@extends('layouts.public_results')

@section('content-header')

@endsection

@section('content')

  @if($authorized)
    {!! Breadcrumbs::render('results.competition.show-public', $competition) !!}

    <h2>{{ $competition->name }} Results By Division</h2>

    @if($competition->divisions->count() == 0)
      <p>There are currently no divisions with published results. Please check back again shortly.</p>
    @endif


    @if($competition->divisions->count() > 0)
      <ul class="list-group">
        @foreach($competition->divisions as $div)
          <li class="list-group-item">{{ link_to_route('results.division.show', $div->name, [$div , $div->access_code])}}</li>

        @endforeach
      </ul>
    @endif
  @endif


  @if(!$authorized)
    <h2>{{ $competition->name }} Results</h2>

    @include('results/competition/access_code_form')
  @endif




@endsection
