@extends('layouts.app')

@section('style')
  @hasSection('style')
    @yield('style')
  @endif
@endsection

@section('body-content')
  @include('navigation/header')

  @if(empty($competition) AND isset($division->competition))
    @php $competition = $division->competition;@endphp
  @endif
  @includeWhen(isset($competition), 'competition.partial.navigation_bar')

  @includeWhen(isset($include_division_navigation_bar), 'division.partial.navigation_bar')

  <div class="collapse content body-width">

    @hasSection('content-header')
      <div class="content-header">
        @yield('content-header')
      </div>
    @endif

    @section('alert')
      @include('alert/all')
    @show

    @yield('content')
  </div>

  @hasSection('breadcrumbs')
    <div class="breadcrumbs-footer body-width">
      @yield('breadcrumbs')
    </div>
  @endif

@endsection

@push('own-script')
  @stack('own-script')
@endpush
