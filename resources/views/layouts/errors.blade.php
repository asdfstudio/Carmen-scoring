@extends('layouts.app')


@section('body-header')
  <div class="body-header body-width">
    <a href="/"><img src="/images/Carmen-Logo-185x54.png" alt="Carmen Scoring System"  /></a>
  </div>
@endsection


@section('body-content')
  <div class="collapse content body-width">
    @yield('content')
  </div>
@endsection
