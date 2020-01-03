@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.competition.division.create',$competition) !!}
@endsection

@section('content-header')
	<h1>Create a division</h1>

	<ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.division.index','Back to All Divisions',[$competition],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')

		{!! form_start($form) !!}
      
      {!! form_until($form, 'rating_system_heading') !!}
      
      <div class="rating-system collection-container form-group" data-prototype="{{ form_row($form->rating_system->prototype()) }}">
        {!! form_row($form->rating_system) !!}
      </div>
      
		{!! form_end($form) !!}

@endsection
