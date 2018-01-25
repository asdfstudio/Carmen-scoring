@extends('layouts.public_results')

@section('breadcrumbs')
	{!! Breadcrumbs::render('results.division.show-public', $division) !!}
@endsection

@section('content')

		<h2>Standings</h2>

		@foreach($division->standings as $standing)
			<div class="standing-container">

				<div class="content-subheader caption {{ $standing->caption_slug }}">
					@if($standing->caption_id == NULL)
						<h3>Overall Standings</h3>
					@else
						<h2>{{ $standing->caption->name }} Standings</h2>
					@endif
				</div>

				@if($standing == false)
				  <p>There are no final standings yet.</p>
				@endif

				@if($standing)
				  @if($standing->is_consensus_scoring)
				    <p class="alert alert-warning">Consensus scoring is used for this division.</p>
				  @endif

				  @include('standing.public_list', ['standing' => $standing, 'showSponsor' => false])

				@endif
			</div>
		@endforeach


@endsection
