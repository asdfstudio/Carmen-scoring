@extends('layouts.simple')

@section('content-header')
	<h1>Award Ceremony</h1>

	<ul class="actions-group">
    @can('show', $division)
      <li>
				{{ link_to_route('organizer.competition.division.show', 'Back to Division', [$division->competition,$division], ['class' => 'action']) }}
			</li>
    @endcan


	</ul>
@endsection

@section('content')

  <div class="individual-awards-container">
    <h2>Individual Awards</h2>

    @include('award.organizer.ceremony_list', ['awards' => $division->awards])
  </div>

	<div class="caption-awards-container">
    @php //dd($division->standings); @endphp
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

				if($limit == 0) continue;

				$standing->choirs = $standing->choirs->where('pivot.final_rank', '<=', $limit)->reverse();
				@endphp

			@endif

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

				@include('standing.ceremony_list', ['standing' => $standing])



			</div>
		@endforeach

	</div>


  <div class="alert alert-info">
    <p>When the Award Ceremony is over, remember to finalize/publish the results of this division. This will allow particants, judges and the general public to view the results.</p>
    <p>{{ link_to_route('organizer.competition.division.show', 'Go to Division', [$division->competition, $division]) }}</p>
  </div>


@endsection
