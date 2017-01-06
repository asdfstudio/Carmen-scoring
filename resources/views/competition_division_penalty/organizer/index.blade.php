@extends('layouts.simple')

@section('breadcrumbs')

@endsection

@section('content-header')
	<h1>Division Penalties</h1>

	<ul class="actions-group">
		@can('createPenalty' , $division)
			<li>
				{{ link_to_route('organizer.competition.division.penalty.create','Create new penalty', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
		@can('managePenalties' , $division)
			<li>
				{{ link_to_route('organizer.competition.division.penalty.manage','Manage division penalties', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
		@can('assignPenalty' , $division)
			<li>
				{{ link_to_route('organizer.competition.division.penalty.assign','Assign a Penalty', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>
@endsection

@section('content')

  @include('penalty.organizer.list')

@endsection
