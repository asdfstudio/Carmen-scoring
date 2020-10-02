@extends('layouts.public_results')

@section('breadcrumbs')
  {!! Breadcrumbs::render('results.division.show-public', $division) !!}
@endsection

@section('content')
  <h2>Audience vote results</h2>
  <ul class="list-group">
    @foreach ($votes as $key => $vote)
        <li class="list-group-item standing">
        <span class="choir">{{$vote->choir->name}}</span>
        <div class="details">
          <span class="final_rank ceremony rank-3">
            {{ $vote->vote_count }}
          </span>
        </div>

      </li>
    @endforeach

  </ul>
@endsection
