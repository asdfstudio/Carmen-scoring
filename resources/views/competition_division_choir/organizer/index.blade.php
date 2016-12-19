@extends('layouts.simple')

@section('breadcrumbs')
{!! Breadcrumbs::render('organizer.competition.division.choir.index',$division->competition,$division) !!}
@endsection

@section('content-header')
	<h1>Manage Choirs</h1>

	<ul class="actions-group">
		@can('addChoir', $division)
			<li>
				{{ link_to_route('organizer.competition.division.choir.create','Add a choir',[$division->competition,$division], ['class' => 'action']) }}
			</li>
		@endcan
	</ul>
@endsection

@section('content')

  @include('competition_division_choir.organizer.list',['choirs' => $division->choirs])

@endsection
