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
				@php
				if($standing->caption_id == NULL)
				{
					$awardSetting = $division->awardSettings->where('caption_id', 0)->first();
				}
				else
				{
					$awardSetting = $division->awardSettings->where('caption_id', $standing->caption_id)->first();
				}

				if ($awardSetting) {
					$limit = $awardSetting->award_count;
				} else {
					$limit = 0;
				}

				$standing->choirs = $standing->choirs->where('pivot.final_rank', '<=', $limit)->reverse();
				@endphp
			@endif

			@if($standing->choirs->count() > 0)
				<div class="standing-container">
					@if($standing->caption_id == NULL)
						<div class="content-subheader caption">
						<h3>Overall Standings</h3>
						</div>
					@else
						<div class="content-subheader caption {{ $standing->caption->background_css }}">
						<h3>{{ $standing->caption->name }} Standings</h3>
						</div>
					@endif

					@include('standing.public_list', ['standing' => $standing ,'showSponsor' => true])

				</div>
			@endif
		@endforeach

    <div class="standing-container">
      <div class="content-subheader caption background-color-3">
        <h3>Audience vote results</h3>
      </div>
      @if($voteResults)
      <ul class="list-group">
        @foreach($voteResults as $key => $result)
        <li class="list-group-item standing">
          <span class="choir">{{$result->choir->name}}</span>
          <div class="details">
            <span class="final_rank ceremony">
              {{$result->vote_count}}
            </span>
          </div>
        </li>
        @endforeach
      </ul>
      @else
        We have no audience vote yet.
      @endif
    </div>





@endsection
