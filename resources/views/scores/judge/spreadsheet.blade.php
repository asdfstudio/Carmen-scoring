<?php
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

?>

<table class="table {{ $responsive_table_class }} table-striped table-bordered toggle-scores scoreboard spreadsheet {{ $is_weighted_class }}">


  @foreach($judge->captions as $caption)

    <?php
    $captionWeighting = 1;

    if($division->captionWeighting->slug == '60-40') :
      if($caption->id == 1) :
        $captionWeighting = 1.5;
      elseif($caption->id == 2) :
        $captionWeighting = 1;
      endif;
    endif;
    ?>

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

    <tr class="caption-header caption-{{ $caption->slug() }}">
      <td>{{ $caption->name }}</td>

      @foreach($choirs as $choir)
        <td>
          &nbsp;
        </td>
      @endforeach
    </tr>

    <!--Caption criteria-->
    <?php
    $criteria = $division->sheet->criteria->where('caption_id', $caption->id);
    ?>
    @foreach($criteria as $criterion)
      <tr>
        <td>{{ $criterion->name }}</td>

        @foreach($choirs as $choir)
          <?php
          $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion.id', $criterion->id)->pluck('score')->first();
          ?>

          <td class="score-gradient-{{ $rawScore * 10 }}" data-choir-id="{{ $choir->id }}" data-criterion-id="{{ $criterion->id }}">

            <span class="score raw">{{ $rawScore }}</span>

            <?php if($rawScore == false) $rawScore = 0; ?>

            @if($round->status_slug == 'active')
              {{ Form::open(['method' => 'POST', 'url' => route('judge.competition.division.round.save_scores', [$division->competition->id, $division->id, $round->id])]) }}

              {{ Form::number("scores[$choir->id][$criterion->id]", $rawScore,['min' => 0, 'max' => 10, 'step' => '0.5', 'class' => 'col-xs-12 score edit ajax-scoring toggle-score-input-popup', 'data-original-score' => $rawScore, 'data-choir-id' => $choir->id, 'data-criterion-id' => $criterion->id, 'data-caption-id' => $caption->id, 'data-score-weighting' => $captionWeighting, 'readonly' => 'readonly']) }}

              {{ Form::close() }}
            @endif

            @if($division->captionWeighting->slug == '60-40')
              <?php
              $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion.id', $criterion->id)->sum('weightedScore');
              ?>
              <span class="score weighted">{{ $weightedScore }}</span>
            @endif
          </td>
        @endforeach
      </tr>
    @endforeach

    <tr class="caption-raw-score caption-{{ $caption->slug() }} caption-id-{{ $caption->id }}">
      <td>
        Total
      </td>

      @foreach($choirs as $choir)
        <td>
          &nbsp;
          <?php
          $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion.caption_id', $caption->id)->sum('score');
          ?>
          <span class="caption-total-score score edit raw" data-caption-id="{{ $caption->id }}" data-choir-id="{{ $choir->id }}" data-original-score="{{ $rawScore }}">{{ $rawScore }}</span>

          @if($division->captionWeighting->slug == '60-40')
            <?php
            $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion.caption_id', $caption->id)->sum('weightedScore');
            ?>
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
        <?php
        $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');
        ?>
        <span class="sum-score score edit raw" data-choir-id="{{ $choir->id }}" data-original-score="{{ $rawScore }}">{{ $rawScore }}</span>

        @if($division->captionWeighting->slug == '60-40')
          <?php
          $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('weightedScore');
          ?>
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


<div id="score-input-popup" class="popup-input-container" data-field="" tabindex="-1">
  <div class="number-selector-container">

    @include('scores.forms.number_selector', ['criterion' => false,'score' => false, 'start' => 1, 'end' => 10, 'interval' => 1])

    @include('scores.forms.number_selector', ['criterion' => false,'score' => false, 'start' => 0.5, 'end' => 9.5, 'interval' => 1, 'class' => 'half'])

  </div>
</div>
