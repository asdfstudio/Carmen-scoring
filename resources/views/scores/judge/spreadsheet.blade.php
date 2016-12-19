<?php
if($division->captionWeighting->slug == '60-40') :
  $toggle_scores = 'toggle-scores';
else :
  $toggle_scores = false;
endif;
?>

<table class="table responsive table-striped table-bordered toggle-scores scoreboard spreadsheet">


  @foreach($judge->captions as $caption)
    <!--Caption heading-->

    <tr>
      <th>&nbsp;</th>

      @foreach($choirs as $choir)
        <th>{{ $choir->full_name }}</th>
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

            @if($round->status_slug == 'active')
              {{ Form::open(['method' => 'POST', 'url' => route('judge.competition.division.round.save_scores', [$division->competition->id, $division->id, $round->id])]) }}

              {{ Form::number("scores[$choir->id][$criterion->id]", $rawScore,['min' => 0, 'max' => 10, 'step' => '0.5', 'class' => 'col-xs-12 score edit ajax-scoring toggle-score-input-popup', 'data-original-score' => $rawScore, 'data-choir-id' => $choir->id, 'data-criterion-id' => $criterion->id]) }}

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

    <tr class="caption-raw-score caption-{{ $caption->slug() }}">
      <td>
        Total
      </td>

      @foreach($choirs as $choir)
        <td>
          <?php
          $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion.caption_id', $caption->id)->sum('score');
          ?>
          <span class="score raw">{{ $rawScore }}</span>

          @if($division->captionWeighting->slug == '60-40')
            <?php
            $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion.caption_id', $caption->id)->sum('weightedScore');
            ?>
            <span class="score weighted">{{ $weightedScore }}</span>
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
        <?php
        $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');
        ?>
        <span class="score raw">{{ $rawScore }}</span>

        @if($division->captionWeighting->slug == '60-40')
          <?php
          $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('weightedScore');
          ?>
          <span class="score weighted">{{ $weightedScore }}</span>
        @endif

      </td>
    @endforeach
  </tr>

  <tr>
    <th>&nbsp;</th>

    @foreach($choirs as $choir)
      <th>{{ $choir->full_name }}</th>
    @endforeach
  </tr>
</table>


<div id="score-input-popup" class="popup-input-container" data-field="" tabindex="-1">
  <div class="number-selector-container">

    @include('scores.forms.number_selector', ['criterion' => false,'score' => false, 'start' => 1, 'end' => 10, 'interval' => 1])

    @include('scores.forms.number_selector', ['criterion' => false,'score' => false, 'start' => 0.5, 'end' => 9.5, 'interval' => 1, 'class' => 'half'])

  </div>
</div>
