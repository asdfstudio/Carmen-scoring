@extends('layouts.simple')

@section('content-header')
  <h1>Add a type to this competition</h1>

  <ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.round.index','Back to all types',[$competition], ['class' => 'action']) }}
		</li>
	</ul>
@endsection

@section('content')
		{!! form($form) !!}
		@include('sheets.partial.info-wrapper')
@endsection

@push('own-scripts')
<script src="/dist/js/validation.js"></script>
@endpush
