@extends('layouts.simple')

@section('content-header')
  <h1>Add a judge to this division</h1>
  <ul class="actions-group">
    <li>
      {{ link_to_route('organizer.competition.division.judge.index','Back to judges', [$division->competition->id, $division->id], ['class' => 'action']) }}
    </li>
  </ul>
@endsection

@section('content')
		{!! form($form) !!}
@endsection

@section('body-footer')
	<script>
    jQuery(document).ready(function($){
      var judgeSelectize = $('.judge_id').selectize({
        allowEmptyOption: true,
        placeholder: 'Select a judge...'
      });
      
      // Clear the Selectize field so that the placeholder will show
      // and validation will detect the field as empty.
      judgeSelectize[0].selectize.clear();
    });
  </script>
@endsection