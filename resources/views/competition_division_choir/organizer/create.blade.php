@extends('layouts.simple')

@section('body-header')
  <style>@import "/css/director-form.css";</style>
@endsection


@section('content-header')
  <h1>Add a choir</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.division.choir.index','Back to choirs',[$division->competition,$division], ['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

		{!! form($form) !!}

@endsection

@section('body-footer')
  <script src="/js/director-form.js"></script>
	<script>
    var ChoirForm = (function() {
      var form;

      var init = function(form){
        this.form = form;
        choirSelectize = $('.choir_id').selectize();
        initSelectize();
      };

      var showNewChoirForm = function(){
        this.form.find('.new_choir_container').show();
        this.form.find('.new_school_container').hide();
        this.form.find('.existing_choir_container').hide();
        this.form.find('.toggle-new-choir-container').hide();
      };

      var showNewSchoolForm = function(){
        this.form.find('.new_school_container').show();
        this.form.find('.existing_school_container').hide();
        this.form.find('.toggle-new-school-container').hide();
      };

      return {
        init: init,
        showNewChoirForm: showNewChoirForm,
        showNewSchoolForm: showNewSchoolForm
      };
    })();
    
    jQuery(document).ready(function($){
      ChoirForm.init($('#create-choir-form'));
      
      $('body').on('click', '.toggle-new-choir-container', function(e) {
        e.preventDefault();
        ChoirForm.showNewChoirForm();
      });

      $('body').on('click', '.toggle-new-school-container', function(e) {
        e.preventDefault();
        ChoirForm.showNewSchoolForm();
      });
    });
  </script>
@endsection