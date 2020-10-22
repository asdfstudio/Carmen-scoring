@extends('layouts.simple')
@php $include_division_navigation_bar = TRUE @endphp

@section('breadcrumbs')

@endsection

@section('content-header')
	<h1>Penalties</h1>

	<ul class="actions-group">
		@can('createPenalty' , $division)
			<li>
                {{ link_to_route('organizer.penalty.create','Create new penalty', [$competition->organization], ['class' => 'action']) }}
			</li>
		@endcan
		@can('managePenalties' , $division)
			<li>
				{{ link_to_route('organizer.penalty.index','Manage Organization Penalties', [$competition->organization], ['class' => 'action']) }}
			</li>
		@endcan
		@can('assignPenalty' , $division)
			<li>
				{{ link_to_route('organizer.competition.division.penalty.assign','Assign a Penalty', [$competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>
@endsection

@section('content')

  @include('penalty.organizer.list')

@endsection
