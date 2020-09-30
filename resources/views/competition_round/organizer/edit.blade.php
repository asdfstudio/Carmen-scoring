@extends('layouts.simple')


@section('content-header')
  <h1>Edit round</h1>

  <ul class="actions-group">
        <li>
            {{ link_to_route('organizer.competition.round.index','Back to all rounds',[$round->competition], ['class' => 'action']) }}
        </li>
    </ul>
@endsection

@section('content')

   {!! form($form) !!}

    @can('destroy', $round)
      <h2>Remove round from this competition</h2>
      {!! form($deleteForm) !!}
    @endcan

@endsection
