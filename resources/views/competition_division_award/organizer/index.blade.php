@extends('layouts.simple')


@section('content-header')
	<h1>Awards</h1>
	<ul class="actions-group">
		@can('createAward', $division)
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

	<h2>Caption Specific Awards</h2>

	@can('update', $division)
		{{ link_to_route('organizer.competition.division.award.settings.edit', 'Edit Award Settings', [$division->competition_id, $division], ['class' => 'action mv']) }}
	@endcan

	@include('division_award_settings.organizer.list', ['awardSettings' => $division->awardSettings])

	<h2>Other Awards</h2>
  @include('award.organizer.list')
@endsection
