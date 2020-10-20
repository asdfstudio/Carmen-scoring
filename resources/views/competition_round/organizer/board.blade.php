@extends('layouts.simple')

@section('breadcrumbs')
    {!! Breadcrumbs::render('organizer.competition.round.index',$competition) !!}
@endsection

@section('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection


@section('content')

<div class="clearfix"></div>

<div class="division-board content-header" id="division-13-board">
    <ul class="actions-group">
        <li>{{ link_to_route('organizer.competition.round.show', 'Back to the Round', [$competition,$round],['class' => 'action']) }}</li>
    </ul>
    <h2>Edit Judges</h2>

    @include('judge.board.board-list')

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
@endpush
