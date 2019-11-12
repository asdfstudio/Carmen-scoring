@extends('layouts.public_results')

@section('breadcrumbs')
	{!! Breadcrumbs::render('results.division.show-public', $division) !!}
@endsection

@section('content')

		<h2>Standings</h2>

		@foreach($division->standings as $standing)
			<div class="standing-container">

				@if($standing->caption_id == NULL)
					<div class="content-subheader caption">
					<h3>Overall Standings</h3>
				@else
					<div class="content-subheader caption {{ $standing->caption->background_css }}">
					<h2>{{ $standing->caption->name }} Standings</h2>
				@endif
				</div>

				@if($standing == false)
				  <p>There are no final standings yet.</p>
				@endif

				@if($standing)

				  @include('standing.public_list', ['standing' => $standing, 'showSponsor' => false])

				@endif
			</div>
		@endforeach


@endsection
