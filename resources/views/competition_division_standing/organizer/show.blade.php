@extends('layouts.simple')

@section('content-header')
	<h1>Final Standings</h1>

	<ul class="actions-group">
    @can('show', $division)
      <li>
				{{ link_to_route('organizer.competition.division.show', 'Back to Division', [$division->competition,$division], ['class' => 'action']) }}
			</li>
    @endcan


	</ul>
@endsection

@section('content')

	@foreach($division->standings as $standing)
		<div class="standing-container">


			@if($standing->caption_id == NULL)
				<div class="content-subheader caption">
					<h2>Overall Standings</h2>
			@else
				<div class="content-subheader caption {{ $standing->caption->background_css }}">
					<h2>{{ $standing->caption->name }} Standings</h2>
			@endif

				@can('update', $standing)

					{{ link_to_route('organizer.competition.division.standing.edit', 'Modify Standings', [$division->competition, $division, $standing], ['class' => 'action']) }}

		    @endcan
			</div>



			@if($standing == false)
		    <p>
		      There are no final standings yet.
		    </p>
		  @endif

			@if($standing)

		  	@include('standing.list', ['standing' => $standing])

		  @endif



		</div>
	@endforeach





@endsection
