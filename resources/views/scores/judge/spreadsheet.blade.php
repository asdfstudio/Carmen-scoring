@php
if($division->captionWeighting->slug == '60-40') :
  $toggle_scores = 'toggle-scores';
  $is_weighted_class = 'weighted-60-40';
else :
  $toggle_scores = false;
  $is_weighted_class = '';
endif;

if($choirs->count() > 2) :
  $responsive_table_class = 'responsive';
else :
  $responsive_table_class = false;
endif;

@endphp
<div class="table-wrapper-responsive">
<table class="table {{ $responsive_table_class }} table-striped table-bordered toggle-scores scoreboard spreadsheet {{ $is_weighted_class }}">


  @foreach($captions as $caption)

    @php
    $captionWeighting = 1;

    if($division->captionWeighting->slug == '60-40') :
      if($caption->id == 1) :
        $captionWeighting = 1.5;
      elseif($caption->id == 2) :
        $captionWeighting = 1;
      endif;
    endif;
    @endphp

    <!--Caption heading-->

    <tr>
      <th><div>&nbsp;</div></th>

      @foreach($choirs as $choir)
        <th>
          <div>
            {{ $choir->full_name }}
          </div>
        </th>
      @endforeach
    </tr>

    <tr class="caption-header {{ $caption->background_css }}">
      <td>{{ $caption->name }}</td>

      @foreach($choirs as $choir)
        <td>&nbsp;

        </td>
      @endforeach
    </tr>

    <!--Caption criteria-->
    @php
    $criteria = $division->sheet->criteria->where('caption_id', $caption->id);
    @endphp
    @foreach($criteria as $criterion)
      <tr>
        <td>
          <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="{{ $criterion->name }}" data-content="{{ $criterion->description }}">{{ $criterion->name }}</a>
        </td>

        @foreach($choirs as $choir)
          @php
          $rawScoreEntry = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_id', $criterion->id)->first();

          if ($rawScoreEntry) {
            $rawScore = $rawScoreEntry->score;
            $roundId = $rawScoreEntry->round_id;
            $divisionId = $rawScoreEntry->division_id;
          } else {
            $rawScore = false;
            $roundId = $round->id;
            $divisionId = $division->id;
          }


          if ($round AND $round->sources AND $round->sources->count() > 0) {

            $firstRound = $round->sources->where('id', $roundId)->first();

            if ($firstRound AND $firstRound->status_slug == 'active') {
              $isRoundScoringActive = true;
            } else {
              $isRoundScoringActive = false;
            }

          } else {
            $isRoundScoringActive = true;
          }
          //dd($rawScoreEntry);
          //$rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_id', $criterion->id)->pluck('score')->first();
          //$



          if ($rawScore == 0) {
            $missingScoresClass = 'missing-score';
          } else {
            $missingScoresClass = '';
          }
          @endphp

          <td class="score-gradient-{{ $rawScore * 10 }} {{ $missingScoresClass }}" data-choir-id="{{ $choir->id }}" data-criterion-id="{{ $criterion->id }}" data-round-id="{{ $roundId }}">

            <span class="score raw">{{ $rawScore }}</span>

            @php if($rawScore == false) $rawScore = 0; @endphp

            @if($isScoringActive AND $isRoundScoringActive)
              {{ Form::open(['method' => 'POST', 'url' => route('judge.competition.division.round.save_scores', [$division->competition->id, $divisionId, $roundId])]) }}

              {{ Form::number("scores[$choir->id][$criterion->id]", $rawScore,[
                'min' => 0,
                'max' => $criterion->max_score,
                'step' => '0.5',
                'class' => 'col-xs-12 score edit ajax-scoring toggle-score-input-popup', 'data-original-score' => $rawScore,
                'data-choir-id' => $choir->id,
                'data-criterion-id' => $criterion->id,
                'data-caption-id' => $caption->id,
                'data-score-weighting' => $captionWeighting,
                'readonly' => 'readonly'
                ]) }}

              {{ Form::close() }}
            @endif

            @if($division->captionWeighting->slug == '60-40')
              @php
              $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_id', $criterion->id)->sum('weightedScore');
              @endphp
              <span class="score weighted">{{ $weightedScore }}</span>
            @endif
          </td>
        @endforeach
      </tr>
    @endforeach

    <tr class="caption-raw-score {{ $caption->lighter_background_css }} caption-id-{{ $caption->id }}">
      <td>
        Total
      </td>

      @foreach($choirs as $choir)
        <td>
          &nbsp;
          @php
          $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('score');
          @endphp
          <span class="caption-total-score score edit raw" data-caption-id="{{ $caption->id }}" data-choir-id="{{ $choir->id }}" data-original-score="{{ $rawScore }}">{{ $rawScore }}</span>

          @if($division->captionWeighting->slug == '60-40')
            @php
            $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');
            @endphp
            <span class="caption-total-weighted-score score weighted" data-caption-id="{{ $caption->id }}" data-choir-id="{{ $choir->id }}" data-original-score="{{ $weightedScore }}">{{ $weightedScore }}</span>
          @endif
        </td>
      @endforeach
    </tr>
  @endforeach

  <tr class="total-score">
    <td>
      Total
    </td>

    @foreach($choirs as $choir)
      <td>
        &nbsp;
        @php
        $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');
        @endphp
        <span class="sum-score score edit raw" data-choir-id="{{ $choir->id }}" data-original-score="{{ $rawScore }}">{{ $rawScore }}</span>

        @if($division->captionWeighting->slug == '60-40')
          @php
          $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('weightedScore');
          @endphp
          <span class="sum-weighted-score score weighted" data-choir-id="{{ $choir->id }}" data-original-score="{{ $weightedScore }}">{{ $weightedScore }}</span>
        @endif

      </td>
    @endforeach
  </tr>

  <tr>
    <th><div>&nbsp;</div></th>

    @foreach($choirs as $choir)
      <th>
        <div class="">
          {{ $choir->full_name }}
        </div>
      </th>
    @endforeach
  </tr>
</table>
</div>

<div id="score-input-popup" class="popup-input-container" data-field="" tabindex="-1">
  <div class="number-selector-container">

    @include('scores.forms.number_selector', ['criterion' => false,'score' => false, 'start' => 1, 'end' => 10, 'interval' => 1])

    @include('scores.forms.number_selector', ['criterion' => false,'score' => false, 'start' => 0.5, 'end' => 9.5, 'interval' => 1, 'class' => 'half'])

  </div>
</div>
