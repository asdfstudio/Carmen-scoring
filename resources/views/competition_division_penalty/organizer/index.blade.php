@extends('layouts.simple')

@section('content-header')
	<h1>Division Penalties</h1>

	<ul class="actions-group">
		@can('create' , 'App\Penalty')
			<li>
				{{ link_to_route('organizer.competition.division.penalty.create','Create new penalty', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
			<li>
				{{ link_to_route('organizer.competition.division.penalty.manage','Manage division penalties', [$division->competition->id, $division->id], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>
@endsection

@section('content')

  @include('penalty.organizer.list')

@endsection
