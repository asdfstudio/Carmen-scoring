@extends('layouts.app')


@section('body-header')
  <div class="body-header body-width">
    <a href="/"><img src="/images/logo-with-text.png" width="185" alt="Carmen Scoring System"  /></a>
  </div>
@endsection


@section('body-content')
  <div class="collapse content body-width">
    @yield('content')
  </div>
@endsection
