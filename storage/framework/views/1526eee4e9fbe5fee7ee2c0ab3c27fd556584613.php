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

<table class="table <?php echo e($responsive_table_class); ?> table-striped table-bordered toggle-scores scoreboard spreadsheet <?php echo e($is_weighted_class); ?>">


  <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

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

      <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <th>
          <div>
            <?php echo e($choir->full_name); ?>

          </div>
        </th>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>

    <tr class="caption-header <?php echo e($caption->background_css); ?>">
      <td><?php echo e($caption->name); ?></td>

      <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td>
          &nbsp;
        </td>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>

    <!--Caption criteria-->
    <?php
    $criteria = $division->sheet->criteria->where('caption_id', $caption->id);
    ?>
    <?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td>
          <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo e($criterion->name); ?>" data-content="<?php echo e($criterion->description); ?>"><?php echo e($criterion->name); ?></a>
        </td>

        <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
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
          ?>

          <td class="score-gradient-<?php echo e($rawScore * 10); ?> <?php echo e($missingScoresClass); ?>" data-choir-id="<?php echo e($choir->id); ?>" data-criterion-id="<?php echo e($criterion->id); ?>" data-round-id="<?php echo e($roundId); ?>">

            <span class="score raw"><?php echo e($rawScore); ?></span>

            <?php if($rawScore == false) $rawScore = 0; ?>

            <?php if($isScoringActive AND $isRoundScoringActive): ?>
              <?php echo e(Form::open(['method' => 'POST', 'url' => route('judge.competition.division.round.save_scores', [$division->competition->id, $divisionId, $roundId])])); ?>


              <?php echo e(Form::number("scores[$choir->id][$criterion->id]", $rawScore,[
                'min' => 0,
                'max' => $criterion->max_score,
                'step' => '0.5',
                'class' => 'col-xs-12 score edit ajax-scoring toggle-score-input-popup', 'data-original-score' => $rawScore,
                'data-choir-id' => $choir->id,
                'data-criterion-id' => $criterion->id,
                'data-caption-id' => $caption->id,
                'data-score-weighting' => $captionWeighting,
                'readonly' => 'readonly'
                ])); ?>


              <?php echo e(Form::close()); ?>

            <?php endif; ?>

            <?php if($division->captionWeighting->slug == '60-40'): ?>
              <?php
              $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_id', $criterion->id)->sum('weightedScore');
              ?>
              <span class="score weighted"><?php echo e($weightedScore); ?></span>
            <?php endif; ?>
          </td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <tr class="caption-raw-score <?php echo e($caption->lighter_background_css); ?> caption-id-<?php echo e($caption->id); ?>">
      <td>
        Total
      </td>

      <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td>
          &nbsp;
          <?php
          $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('score');
          ?>
          <span class="caption-total-score score edit raw" data-caption-id="<?php echo e($caption->id); ?>" data-choir-id="<?php echo e($choir->id); ?>" data-original-score="<?php echo e($rawScore); ?>"><?php echo e($rawScore); ?></span>

          <?php if($division->captionWeighting->slug == '60-40'): ?>
            <?php
            $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');
            ?>
            <span class="caption-total-weighted-score score weighted" data-caption-id="<?php echo e($caption->id); ?>" data-choir-id="<?php echo e($choir->id); ?>" data-original-score="<?php echo e($weightedScore); ?>"><?php echo e($weightedScore); ?></span>
          <?php endif; ?>
        </td>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  <tr class="total-score">
    <td>
      Total
    </td>

    <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <td>
        &nbsp;
        <?php
        $rawScore = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');
        ?>
        <span class="sum-score score edit raw" data-choir-id="<?php echo e($choir->id); ?>" data-original-score="<?php echo e($rawScore); ?>"><?php echo e($rawScore); ?></span>

        <?php if($division->captionWeighting->slug == '60-40'): ?>
          <?php
          $weightedScore = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('weightedScore');
          ?>
          <span class="sum-weighted-score score weighted" data-choir-id="<?php echo e($choir->id); ?>" data-original-score="<?php echo e($weightedScore); ?>"><?php echo e($weightedScore); ?></span>
        <?php endif; ?>

      </td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tr>

  <tr>
    <th><div>&nbsp;</div></th>

    <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <th>
        <div class="">
          <?php echo e($choir->full_name); ?>

        </div>
      </th>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </tr>
</table>


<div id="score-input-popup" class="popup-input-container" data-field="" tabindex="-1">
  <div class="number-selector-container">

    <?php echo $__env->make('scores.forms.number_selector', ['criterion' => false,'score' => false, 'start' => 1, 'end' => 10, 'interval' => 1], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php echo $__env->make('scores.forms.number_selector', ['criterion' => false,'score' => false, 'start' => 0.5, 'end' => 9.5, 'interval' => 1, 'class' => 'half'], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  </div>
</div>
