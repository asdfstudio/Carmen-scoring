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

  <div id="modal-cover" style="display: none"></div>
  <div id="modal" style="display: none"></div>

  <!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script> -->

@endsection

@push('own-scripts')
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="/dist/js/vendor/mustache.min.js"></script>
  <script src="/dist/js/board.js"></script>
	<script src="/dist/js/forms.js"></script>
  <script src="/dist/js/director-form.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {

      $('.add-resource').on('click', function(e) {
        e.preventDefault();
        var type = $(this).data('resource-type');
        Resource.add(type);
      });

      $('.import-resource').on('click', function(e) {
        e.preventDefault();
        Resource.importt('import');
      });

      $('.edit-resource').on('click', function(e) {
        e.preventDefault();

        const arrAllCaptions = $(this).data('all-captions').split('-');
        const arrJudgeCaptionsId = $(this).data('captions').split('-');

        let fHtml = '<form id="swal_edit_judge_form">';
        for (let i = 0; i < arrAllCaptions.length; i += 2) {
          const id = arrAllCaptions[i];
          const name = arrAllCaptions[i+1];
          if(id) {
            const idx = (arrJudgeCaptionsId || []).findIndex(element => element === id);
            fHtml += `<div class="d-flex"><input id="dg_modal_caption_${id}" class="dg-mr-4" type="checkbox" name="caption_id[]" value="${id}" ${idx>-1 ? 'checked="checked"' : ''}/>
                      <label for="dg_modal_caption_${id}">${name}</label></div>`;
          }
        }
        fHtml += '</form>';

        const judgeName = $(this).parent().siblings('span.name').text();

        Swal.fire({
          title: `Choose captions for ${judgeName} to score`,
          html: fHtml,
          showCancelButton: true,
          confirmButtonText: "Update",
          focusConfirm: false,
          showLoaderOnConfirm: true,
          allowOutsideClick: () => !Swal.isLoading(),
          preConfirm: (result) => {
            const url = $(this).attr('href');
            let formData = $('form#swal_edit_judge_form').serialize();

            if (result && !formData) {
              Swal.showValidationMessage('Request failed: There are no selected captions!');
            }
            else if (result && formData){
              formData += `&_token=${$(this).data('csrf-token')}&_method=PATCH`;
              return new Promise(function(resolve, reject) {
                $.ajax({
                  data: formData,
                  dataType: 'json',
                  method: 'POST',
                  url: url,
                }).done(resolve).fail(reject);
              });
            }
          }
        })
        .then((result) => {
          console.log('result:', result)
          if (result.value) {
            Swal.fire({
              title: 'Success!',
              html: `Captions for <b>${judgeName}</b> have been updated successfully!`,
              icon: 'success',
            })
            .then(() => {
              let captionsEl = '';
              let captions = '';
              result.value.forEach(element => {
                captionsEl += `<li class="background-color-${element.color_id} caption label">${element.name}</li>`;
                captions += `${element.id}-`;
              });
              console.log('captions:', captions)
              $(this).parent().siblings('ul').html(captionsEl);
              $(this).attr('data-captions', captions);
              $(this).data('captions', captions);
            });
          }
        })
        .catch(err => {
          console.log('error:', err)
          if(err) Swal.fire({
            title: 'Failed',
            text: 'Something went wrong!',
            icon: 'error',
          });
        });
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
@endpush
