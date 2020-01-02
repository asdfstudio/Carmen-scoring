@extends('layouts.simple')

@section('breadcrumb')
	{!! Breadcrumbs::render('organizer.competition.division.index',$competition) !!}
@endsection

@section('content-header')
	<h1>Manage Divisions</h1>

	@can('createDivision', [$competition])
		{{ link_to_route('organizer.competition.division.create', 'Add a division', [$competition], ['class' => 'action']) }}
	@endcan
@endsection

@section('content')

  @include('competition_division.organizer.table',['divisions' => $competition->divisions])

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