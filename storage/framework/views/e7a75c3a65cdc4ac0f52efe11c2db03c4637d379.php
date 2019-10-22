<?php if(!$division->sheet->criteria->isEmpty()): ?>
<?php echo Form::open(array('route' => array('judge.competition.division.round.choir.save_scores',$division->competition,$division,$round,$choir), 'method' => 'post', 'class' => 'scorecard autosave')); ?>

<div class="scorecard">



  <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <div class="caption-container">

      <div class="caption-heading">
        <?php echo e($caption->name); ?>

      </div>

      <?php $__currentLoopData = $division->sheet->criteria->where('caption_id', $caption->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="criterion-container" data-criterion-id="<?php echo e($criterion->id); ?>">
        <div class="criterion" data-criterion-id="<?php echo e($criterion->id); ?>">
          <?php echo e($criterion->name); ?>

        </div>

        <div class="criterion-description">
          <?php echo e($criterion->description); ?>

        </div>



        <div class="score" data-criterion-id="<?php echo e($criterion->id); ?>">
          <?php $rawScore = $rawScores->where('criterion_id', $criterion->id)->where('judge_id', $judge->id)->where('choir_id', $choir->id)->pluck('score');?>
          <?php $score = $rawScore->first(); ?>
          <?php echo e(Form::text("scores[$criterion->id]", $score, ['data-criterion-id' => $criterion->id, 'readonly' => 'readonly', 'required' => 'required', 'data-original-score' => $rawScore, 'class' => 'criterion-score-input'])); ?>

        </div>

        <div class="number-selector-container">

          <?php echo $__env->make('scores.forms.number_selector', ['criterion' => $criterion,'score' => $score, 'start' => 1, 'end' => $criterion->max_score, 'interval' => 1], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

          <?php if(!env('IS_WORKSHOP_ENABLED')): ?>
            <?php echo $__env->make('scores.forms.number_selector', ['criterion' => $criterion,'score' => $score, 'start' => 0.5, 'end' => ($criterion->max_score - 0.5), 'interval' => 1, 'class' => 'half'], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
          <?php endif; ?>

        </div>

      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  <div class="caption-container">
    <div class="caption-heading">
      Feedback / Comments for Choir
    </div>

    <?php echo e(Form::textarea('comment', $comment, ['placeholder' => 'Enter comments/feedback for choir..'])); ?>

  </div>

  <div class="submit-container">
    <?php echo e(Form::submit('Save Scores & Stay',['class' => 'btn btn-primary btn-lg', 'name' => 'save_stay'])); ?>

    <?php echo e(Form::submit('Save Scores & Back to All Choirs',['class' => 'btn btn-primary btn-lg', 'name' => 'save_go'])); ?>

  </div>

</div>
<?php echo Form::close(); ?>

<?php endif; ?>


<div id="autosave-alert-box" class="hide">Autosaving scores...</div>
