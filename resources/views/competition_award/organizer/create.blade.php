@extends('layouts.simple')

@section('content-header')
  <h1>Create an award</h1>

  <ul class="actions-group">
    @can('showAll','App\Award')
        <li>
            {{ link_to_route('organizer.competition.show','Back to competition', [$competition->id], ['class' => 'action']) }}
        </li>
	@endcan
  </ul>
@endsection

@section('content')

		{!! form($form) !!}

@endsection
