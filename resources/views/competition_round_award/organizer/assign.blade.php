@extends('layouts.simple')

@php $include_round_navigation_bar = TRUE @endphp

@section('content-header')
	<h1>Assign Type Awards</h1>

	<ul class="actions-group">
		@can('showAll','App\Award')
			<li>
				{{ link_to_route('organizer.competition.round.award.index','Back to awards', [$round->competition,$round], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>
@endsection

@section('content')

	{!! Form::open(array('route' => array('organizer.competition.round.award.update_assignment',$round->competition,$round), 'method' => 'post')) !!}

  @include('award.organizer.assign')


	{{ Form::submit('Save Awards', ['class' => 'btn btn-primary btn-lg']) }}

	{!! Form::close() !!}

@endsection
