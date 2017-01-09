@extends('layouts.public_results')

@section('content-header')

@endsection

@section('content')

	<h2>{{ $round->name}} : {{ $choir->full_name }}</h2>

	@include('scores.public.choir_raw',['division' => $division, 'judge' => $round->division->judges->first()])

@endsection
