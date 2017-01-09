@extends('layouts.public_results')

@section('content-header')

@endsection

@section('content')

		<h2 id="awards">Awards</h2>

		<div class="individual-awards-container">
		  <h3>Individual Awards</h3>

		  @include('award.organizer.ceremony_list', ['awards' => $division->awards])
		</div>

		@foreach($division->standings as $standing)
			<div class="standing-container">

				<div class="content-subheader caption {{ $standing->caption_slug }}">
					@if($standing->caption_id == NULL)
						<h3>Overall Standings</h3>
					@else
						<h3>{{ $standing->caption->name }} Standings</h3>
					@endif
				</div>

				@if($standing == false)
				  <p>There are no final standings yet.</p>
				@endif

				@if($standing)
		      <?php
		      if($standing->caption_id == NULL)
		      {
		        $limit = $division->overall_award_count;
		      }
		      else
		      {
		        $column_name = $standing->caption->slug().'_award_count';
		        $limit = $division->{$column_name};
		      }

		      $standing->choirs = $standing->choirs->take($limit)->reverse();
		      ?>
				  @include('standing.public_list', ['standing' => $standing])

				@endif
			</div>
		@endforeach

@endsection
