@extends('layouts.simple')

@section('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.show',$competition,$division) !!}
@endsection

@section('content')
	<ul class="actions-group mv">
		@can('activateScoring', $division->rounds->first())
			<li>{!! form($activateScoringForm) !!}</li>
		@endcan

		@if($division->rounds->first() && $division->rounds->first()->status_slug() != 'completed' && (auth()->user()->isAdmin() || auth()->user()->can('completeScoring', $division->rounds->first())))
			<li>{!! form($completeScoringForm) !!}</li>
		@endif

		@can('finalizeScoring', $division)
			<li>{!! form($finalizeScoringForm) !!}</li>
		@endcan

		@can('update', $division)
			<li>{{ link_to_route('organizer.competition.division.edit', 'Edit Division', [$competition,$division],['class' => 'action']) }}</li>
		@endcan

		<li>{{ link_to_route('organizer.competition.division.settings', 'Exit Set Up Mode', [$competition,$division],['class' => 'action']) }}</li>

	</ul>

	<div class="clearfix"></div>

  <div class="division-board" id="division-13-board">
    <h2>{{ $division->name }}</h2>

    @include('choir.board.board-list')

		@include('judge.board.board-list')

		@include('round.board.board-list')

  </div> 
  <!-- end board-->

  <div id="modal-cover" style="display: none"></div>
  <div id="modal" style="display: none"></div>

@endsection

@push('own-scripts')
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="/dist/js/vendor/mustache.min.js"></script>
  <script src="/dist/js/board.js"></script>
	<script src="/dist/js/forms.js"></script>
  <script src="/dist/js/director-form.js"></script>
@endpush
