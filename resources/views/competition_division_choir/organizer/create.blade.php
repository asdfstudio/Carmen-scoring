@extends('layouts.simple')

@section('content-header')
  <h1>Add a choir</h1>

  <ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.division.choir.index','Back to choirs',[$division->competition,$division], ['class' => 'action']) }}
		</li>
	</ul>
@endsection

@section('content')



		{!! form($form) !!}

@endsection
