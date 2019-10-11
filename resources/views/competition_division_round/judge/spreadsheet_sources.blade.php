@extends('layouts.simple')

@section('content-header')
  <h1>Source Division/Round Scores</h1>
@endsection


@section('division_navigation_bar')

@endsection


@section('round_navigation_bar')
  @if (isset($round) AND isset($division->rounds))
    <div class="round-navigation-bar body-width">
      <ul class="round-navigation">
        @foreach ($division->rounds as $rd)
          @php $active_class = $rd->id == $round->id ? 'active' : '';@endphp
          <li class="round-{{ $rd->status_slug }}">
            <a href="{{ route('judge.round.scores.summary', [$competition, $division, $rd]) }}" class="{{ $active_class }}">
              {{ $rd->name }}

              {!! $rd->status_label('round-navigation-link-status') !!}
            </a>
          </li>
        @endforeach
      </ul>
    </div>
  @endif
@endsection


@section('content')


    <ul class="list-group horizontal">

      @if($division->captionWeighting->slug == '60-40')
    		<li class="list-group-item">
    			@php $active = $division->captionWeighting->slug == '60-40' ? 'active division-scoring-method' : false; @endphp
    			<a class="score-view-toggle {{ $active }}" href="#weighted" data-score-view="weighted">Weighted</a>

    			@if($active)
    				<span>({{ $division->captionWeighting->full_name }})</span>
    			@else
    				<span>({{ $division->captionWeighting->name }})</span>
    			@endif

    		</li>
      @endif


      @php $active = $division->captionWeighting->slug == '50-50' ? 'active division-scoring-method' : false; @endphp
  		<li class="list-group-item">
  			<a class="score-view-toggle {{ $active }}" href="#raw" data-score-view="raw">Raw</a>
  		</li>

      @if($isScoringActive)
        <li class="list-group-item">
    			<a class="score-view-toggle" href="#edit" data-score-view="edit">Edit Scores</a>
    		</li>
      @endif
  	</ul>

  @include('scores.spreadsheet-legend')

  @include('scores.judge.spreadsheet',[
    'choirs' => $choirs,
    'division' => $division,
    'judge' => $judge,
    //'round' => $round
  ])

@endsection


@section('body-footer')
    <!--  Decide if we want to split the spreadsheet table  -->
    @php //$splitTheTable = $round->choirs->count() > 1 ? 'true' : 'false'; @endphp
    @php $splitTheTable = 'true'; @endphp
    <script type="text/javascript">
      splitTheTable = {{ $splitTheTable }}
    </script>

  <script src="/js/responsive-tables.js"></script>
@endsection
