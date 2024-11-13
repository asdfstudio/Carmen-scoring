@extends('layouts.simple')
@php $include_division_navigation_bar = TRUE @endphp

@section('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.show',$competition,$division) !!}
@endsection

@section('content')

<div class="clearfix"></div>

<div class="division-board content-header" id="division-13-board">
    <ul class="actions-group">
        <li>{{ link_to_route('organizer.competition.division.show', 'Back to the Class', [$competition,$division],['class' => 'action']) }}</li>
    </ul>
    <h2>Edit Ensembles</h2>

    @include('choir.board.board-list')

</div>

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
