@extends('layouts.simple')

@section('content')

	<h1>Assign Penalties to {{ $choir->name }}</h1>

  @include('alert/all')

  @include('penalty.organizer.assign_choice_list')

@endsection
