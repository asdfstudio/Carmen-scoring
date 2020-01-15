
@if(!$choirs->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Choir</th>

    @if(!$judges->isEmpty())
    	@foreach($judges as $judge)
      	<th>{{ link_to_route('organizer.round.scores.judge.show',$judge->full_name,[$round->division->competition,$round->division,$round,$judge]) }}</th>
      @endforeach

      <th>Total</th>
    @endif
  </tr>

  @foreach($choirs as $choir)
  <tr>
  	<td>{{ link_to_route('organizer.round.scores.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir]) }}</td>

    @if(!$judges->isEmpty())
    	@foreach($judges as $judge)
      <td data-choir-id="{{ $choir->id }}" data-judge-id="{{ $judge->id }}">

      @if($round->is_completed ?? $judge->id == Auth::user()->person_id ?? Auth::user()->isAdmin())
				@php
        // Raw scores
        $rawScore = $rawScores->where('judge_id', $judge->id)->where('choir_id',$choir->id)->sum('score');
        $rawScoreLink = NULL;

        if($rawScore) :
          $rawScoreLink = link_to_route('organizer.round.scores.choir.judge.show',$rawScore,[$round->division->competition,$round->division,$round,$choir,$judge], ['class' => 'raw-score-total']);
        endif;

        $rawScoreMusic = $rawScores->where('judge_id', $judge->id)->where('choir_id', $choir->id)->where('criterion_caption_id', 1)->sum('score');

        $rawScoreShow = $rawScores->where('judge_id', $judge->id)->where('choir_id', $choir->id)->where('criterion_caption_id', 2)->sum('score');

        // Weighted scores
        $weightedScoreMusic = $weightedScores->where('judge_id', $judge->id)->where('choir_id', $choir->id)->where('criterion_caption_id', 1)->sum('weightedScore');

        $weightedScoreShow = $weightedScores->where('judge_id', $judge->id)->where('choir_id', $choir->id)->where('criterion_caption_id', 2)->sum('weightedScore');

        $weightedScore = $weightedScores->where('judge_id', $judge->id)->where('choir_id', $choir->id)->sum('weightedScore');

        // Raw rankings

        // Weighted rankings
        @endphp
        @if($rawScoreLink) {{ $rawScoreLink }} @endif

        <span class="raw-score-music">{{ $rawScoreMusic }}</span>
        <span class="raw-score-show">{{ $rawScoreShow }}</span>

        <span class="weighted-score-total">{{ $weightedScore }}</span>
        <span class="weighted-score-music">{{ $weightedScoreMusic }}</span>
        <span class="weighted-score-show">{{ $weightedScoreShow }}</span>
      @else
      	-
      @endif
    </td>
      @endforeach
    @endif

    <td>
    	@if($round->is_completed ?? Auth::user()->isAdmin())
				@php $aggregateScore = $rawScores->where('choir_id',$choir->id)->sum('score');@endphp
        {{ $aggregateScore }}
      @else
      	-
      @endif
    </td>
  </tr>
  @endforeach
</table>
</div>
@endif
