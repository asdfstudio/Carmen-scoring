@extends('layouts.simple')

@section('content-header')
	<h1>Assign Division Awards</h1>

	<ul class="actions-group">
		@can('showAll','App\Award')
			<li>
				{{ link_to_route('organizer.competition.division.award.index','Back to awards', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>
@endsection

@section('content')

	{!! Form::open(array('route' => array('organizer.competition.division.award.update_assignment',$division->competition,$division), 'method' => 'post')) !!}

  @include('award.organizer.assign')


	{{ Form::submit('Save Awards', ['class' => 'btn btn-primary btn-lg']) }}

	{!! Form::close() !!}

@endsection
