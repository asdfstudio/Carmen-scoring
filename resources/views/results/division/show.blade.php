@extends('layouts.public_results')

@section('breadcrumbs')
	{!! Breadcrumbs::render('results.division.show-public', $division) !!}
@endsection

@section('content')

		<h2 id="awards">Awards</h2>

		<div class="individual-awards-container">
		  <h3>Individual Awards</h3>

		  @include('award.organizer.ceremony_list', ['awards' => $division->awards])
		</div>

		@foreach($division->standings as $standing)

			@if($standing)
				<?php
				if($standing->caption_id == NULL)
				{
					$limit = $division->awardSettings->where('caption_id', 0)->first()->award_count;
				}
				else
				{
					$limit = $division->awardSettings->where('caption_id', $standing->caption_id)->first()->award_count;
				}

				$standing->choirs = $standing->choirs->take($limit)->reverse();
				?>
			@endif

			@if($standing->choirs->count() > 0)
				<div class="standing-container">
					<div class="content-subheader caption {{ $standing->caption_slug }}">
						@if($standing->caption_id == NULL)
							<h3>Overall Standings</h3>
						@else
							<h3>{{ $standing->caption->name }} Standings</h3>
						@endif
					</div>

					@include('standing.public_list', ['standing' => $standing, 'showSponsor' => true])

				</div>
			@endif
		@endforeach

@endsection
