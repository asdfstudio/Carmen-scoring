@extends('layouts.simple')


@section('content-header')
	<h1>Awards</h1>

	<ul class="actions-group">
		@can('create' , ['App\Award'])
			<li>
				{{ link_to_route('organizer.competition.division.award.create','Create new award', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
		@can('manage' , ['App\Award', $division])
		  <li>
				{{ link_to_route('organizer.competition.division.award.manage','Manage division awards', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan

		@can('assign' , ['App\Award', $division])
			<li>
				{{ link_to_route('organizer.competition.division.award.assign', 'Assign awards', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>

@endsection



@section('content')
  @include('award.organizer.list')
@endsection
