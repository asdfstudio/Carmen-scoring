@extends('layouts.public_results')

@section('content-header')
	<h1>{{ $division->competition->name }}, {{ $division->name }} Results</h1>
@endsection

@section('content')

  <div class="individual-awards-container">
    <h2>Individual Awards</h2>

    @include('award.organizer.ceremony_list', ['awards' => $division->awards])
  </div>

	@foreach($division->standings as $standing)
		<div class="standing-container">

			<div class="content-subheader caption {{ $standing->caption_slug }}">
				@if($standing->caption_id == NULL)
					<h2>Overall Standings</h2>
				@else
					<h2>{{ $standing->caption->name }} Standings</h2>
				@endif
			</div>



			@if($standing == false)
		    <p>
		      There are no final standings yet.
		    </p>
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
		  	@include('standing.ceremony_list', ['standing' => $standing])

		  @endif



		</div>
	@endforeach



@endsection
