@extends('layouts.simple')

@section('content-header')
	<h1>Assign Competition Awards</h1>

	<ul class="actions-group">
		@can('showAll','App\Award')
			<li>
				{{ link_to_route('organizer.competition.show','Back to competition', [$competition->id], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>
@endsection

@section('content')

	{!! Form::open(array('route' => array('organizer.competition.award.update_assignment',$competition), 'method' => 'post')) !!}

  @include('award.organizer.assign')


	{{ Form::submit('Save Awards', ['class' => 'btn btn-primary btn-lg']) }}

	{!! Form::close() !!}

@endsection
