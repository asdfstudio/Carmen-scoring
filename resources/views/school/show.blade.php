@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.school.show', $school) !!}

		<h1>School Details</h1>

		@include('school.partial.single')

    @if($school->place)
			@include('place.show',['place' => $school->place])
    @endif

    @if($school->choirs)
			@include('choir.table',['choirs' => $school->choirs])
    @endif
@endsection
