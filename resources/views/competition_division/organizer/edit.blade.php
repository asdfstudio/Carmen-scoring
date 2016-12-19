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

    @can('destroy', $division)
      <hr>

      <h3>Delete this division?</h3>
      <p class="alert alert-danger">This is a permanent, irrecoverable action. Proceed with caution.</p>
      {!! form($deleteForm) !!}
    @endcan


@endsection
