@extends('layouts.app')

@section('content')


  @include('division.partial.single',['division' => $round->division])

  @include('scores.choir_raw',['division' => $round->division])

@endsection
