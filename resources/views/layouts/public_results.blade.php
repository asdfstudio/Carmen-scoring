@extends('layouts.app')


@section('body-header')
  <div class="body-header body-width">
    <a href="/"><img src="/images/logo-with-text.png" width="185" alt="Carmen Scoring System"  /></a>

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

          @foreach($division->rounds as $round)
            <li>
              @php
              $active = $current_page == 'round_'.$round->id ? 'active' : false;
              @endphp
        			<a href="{{ route('results.division.round.show', [$division, $round, $access_code]) }}" class="{{ $active }} action">Division Scores</a>
        		</li>

            @foreach($round->targets as $target)
              @if($target AND $target->sources->count() > 1)
                <li>
                  @php
                  $active = $current_page == 'round_shared_'.$round->id ? 'active' : false;
                  @endphp
            			<a href="{{ route('results.division.round-shared.show', [$division, $round, $target->id, $access_code]) }}" class="{{ $active }} action">Scores for All Divisions</a>
            		</li>
              @endif
            @endforeach
          @endforeach
      	</ul>
      @endif

    @endif

    @yield('content')
  </div>
@endsection
