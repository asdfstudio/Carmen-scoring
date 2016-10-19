@extends('layouts.simple')

@section('breadcrumbs')
  {!! Breadcrumbs::render('organizer.competition.division.edit',$competition,$division) !!}
@endsection

@section('content-header')
  <h1>Edit a division</h1>

  <ul class="actions-group">
		<li>{{ link_to_route('organizer.competition.division.settings','Back to Settings',[$competition, $division],['class' => 'action']) }}</li>
	</ul>
@endsection

@section('content')





		{!! form($form) !!}


@endsection
