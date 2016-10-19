@extends('layouts.simple')


@section('content-header')
	<h1>Penalties</h1>

	<ul class="actions-group">
		@can('create' , 'App\Penalty')
		  <li>
				{{ link_to_route('organizer.penalty.create','Add a penalty',NULL,['class' => 'action']) }}
			</li>
		@endcan
	</ul>

@endsection

@section('content')


	<p>
		This page lists all of your available penalties for your organization. You can choose which penalties (or none at all) to make available in your competition divisions.
	</p>



  @include('penalty.organizer.list')

@endsection
