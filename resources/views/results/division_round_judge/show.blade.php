@extends('layouts.public_results')

@section('content-header')

@endsection

@section('content')

	<h2>{{ $round->name}} : {{ $judge->full_name }}</h2>

	@include('scores.public.judge_raw')

@endsection
