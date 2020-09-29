@extends('votes.layout')

@section('style')
  <link rel="stylesheet" type="text/css" href="/assets/css/dark_style.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/dark_responsive.css" />
@endsection

@section('content')
  <header>
    @include('votes.partial.header')
  </header>

  @include('votes.partial.banner')
  @include('votes.partial.solo-users')
@endsection
