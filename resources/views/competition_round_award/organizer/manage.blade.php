@extends('layouts.simple')

@php $include_round_navigation_bar = TRUE @endphp

@section('content')

	<div class="content-header">

		<h1>Manage Round Awards</h1>

		<ul class="actions-group">
			@can('showAll','App\Award')
				<li>
					{{ link_to_route('organizer.competition.round.award.index','Back to awards', [$round->competition->id, $round->id], ['class' => 'action']) }}
				</li>
			@endcan
		</ul>

	</div>

	<ul class="actions-group">
		<li class="division-check">
			<a href="#" class="check-all action secondary" data-checkbox="awards">Check all</a>
		</li>
		<li class="division-check">
			<a href="#" class="uncheck-all action secondary" data-checkbox="awards">Uncheck all</a>
		</li>
	</ul>

	{!! Form::open(array('route' => array('organizer.competition.round.award.update',$round->competition,$round), 'method' => 'post')) !!}

	<h2>Custom Awards</h2>
  @include('award.organizer.choice_list')

	<h2>Standard Awards</h2>
  @include('award.organizer.choice_list', ['awards' => $standard_awards])

	{{ Form::submit('Save Awards', ['class' => 'btn btn-primary btn-lg']) }}

	{!! Form::close() !!}

@endsection
