@extends('layouts.app')

@section('content')

		{!! Breadcrumbs::render('admin.judge.show', $judge) !!}

		<h1>Judge Details</h1>

    @include('person.partial.single',['person' => $judge])


@endsection
