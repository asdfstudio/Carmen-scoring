@extends('votes.layout')

@section('style')
  <link rel="stylesheet" type="text/css" href="/assets/css/bright_style.css" />
  <link rel="stylesheet" type="text/css" href="/assets/css/bright_responsive.css" />
@endsection

@section('extra_class')style="background: #c3e1ff;"@endsection
@section('content')
  <header class="light">
    @include('votes.partial.header')
  </header>

  @include('votes.partial.banner')
  @include('votes.partial.users')
@endsection
