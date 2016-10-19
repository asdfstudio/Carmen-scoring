@extends('layouts.simple')

@section('content')

	{!! Breadcrumbs::render('organizer.competition.division.choir.show',$division->competition,$division, $choir) !!}

	<h1>Competition > Divisions > Choirs > {{ $choir->name }}</h1>



  @include('choir.partial.single')

  @include('school.partial.single',['school' => $choir->school])

  @include('competition_division_choir.organizer.delete',['form' => $form])

@endsection
