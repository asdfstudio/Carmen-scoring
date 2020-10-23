@extends('layouts.app')

@section('body-header')
  <div class="body-header body-width">
    <a href="/"><img src="/images/logo-with-dark-text.png" width="185" alt="Carmen Scoring System"  /></a>

    @if(isset($division))
      <div class="heading-container">
        <h1>{{ $division->competition->name}}</h1>
        <h2 class="subheader">{{ $division->competition->place->city_state() }}</h2>
      </div>
    @endif
  </div>
@endsection



@section('body-content')
  <div class="collapse content body-width">
    @yield('breadcrumbs')

    @if(isset($division))
      <h1 class="division-heading">{{ $division->name }} Results</h1>

      @if(isset($access_code))
        <ul class="actions-group centered">
      		<li>
      			<a href="{{ route('results.division.show', [$division, $access_code]) }}" class="@if($current_page == 'awards') active @endif action">Awards</a>
      		</li>
      		<li>
      			<a href="{{ route('results.division.standings', [$division, $access_code]) }}" class="@if($current_page == 'standings') active @endif action">Standings</a>
      		</li>

          <li>
            <a href="{{ route('results.division.audience-vote-results', [$division, $access_code]) }}" class="@if($current_page == 'vote-results') active @endif action">Audience Vote Results</a>
          </li>
      	</ul>
      @endif

    @endif

    @yield('content')
  </div>
@endsection
