@extends('layouts.simple')

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

		<li>{{ link_to_route('organizer.competition.division.show', 'Exit Set Up Mode', [$competition,$division],['class' => 'action']) }}</li>

	</ul>

	<div class="clearfix"></div>



  <div class="division-board" id="division-13-board">
    <h2>{{ $division->name }}</h2>
    <!--<a href="edit-division">Edit Division</a>-->

    @include('choir.board.board-list')

		@include('judge.board.board-list')

		@include('round.board.board-list')



  </div> <!-- end board-->




  <div id="modal-cover"></div>
  <div id="modal"></div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>

  <script src="/js/mustache.min.js"></script>
  <script src="/js/board.js"></script>
	<script src="/js/forms.js"></script>

  <script type="text/javascript">
  $(document).ready(function() {

    $('.add-resource').on('click', function(e) {
      e.preventDefault();
      var type = $(this).data('resource-type');
      Resource.add(type);
    });

    $('.edit-resource').on('click', function(e) {
      e.preventDefault();
      var type = $(this).data('resource-type');
      var id = $(this).data('resource-id');
      var resource = $(this).data('resource');
      Resource.edit(type, id, resource);
    });

    $('a.remove-resource').on('click', function(e) {
      e.preventDefault();
      Resource.remove(this);
    });

		$('form.remove-resource').on('submit', function(e) {
      e.preventDefault();
      Resource.remove(this);
    });


    $('#modal').on('submit', 'form', function(e) {
      e.preventDefault();
      var form = $(this);
      Resource.save(form);
    });

    $('#modal-cover').on('click', function(e) {
      e.preventDefault();
      Modal.close();
    });

		/*$('body').on('ready', '.add-resource-form', function(e) {
      e.preventDefault();
			console.log('resource form ready');
      ChoirForm.init();
    });*/


		$('body').on('click', '.toggle-new-choir-container', function(e) {
      e.preventDefault();
			ChoirForm.init($(this).parents('form'));
			ChoirForm.showNewChoirForm();
    });

		$('body').on('click', '.toggle-new-school-container', function(e) {
      e.preventDefault();
			ChoirForm.init($(this).parents('form'));
			ChoirForm.showNewSchoolForm();
    });

		$('body').on('click', '.toggle-new-judge-container', function(e) {
      e.preventDefault();
			JudgeForm.init($(this).parents('form'));
			JudgeForm.showNewJudgeForm();
    });

		$('body').on('change', 'input[name="choir_source"]', function(e) {
      e.preventDefault();
			RoundForm.init($(this).parents('form'));
			RoundForm.toggleChoirSource();
    });



  });

  </script>

@endsection
