
<table class="table table-striped table-bordered scoreboard toggle-scores">
  <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <?php $captionTotalRank = $scoreboard->rankedScores->total_rank($caption->id);?>
    <?php $totalWeightedRank = $scoreboard->rankedScores->total_weighted_rank($caption->id); ?>
    <?php $totalRawRank = $scoreboard->rankedScores->total_raw_rank($caption->id); ?>

    <tr class="caption-header <?php echo e($caption->background_css); ?>">
      <th colspan="30">
        <?php echo e($caption->name); ?>

      </th>
    </tr>

    <tr>
      <th></th>

      <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <th>
          <?php if($show_links): ?>
            <?php echo e(link_to_route('results.division.round.judge.show', $judge->full_name, [$division, $round, $judge, $access_code])); ?>

          <?php else: ?>
            <?php echo e($judge->full_name); ?>

          <?php endif; ?>
        </th>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <th>Total</th>
      <th>Place</th>

      <?php if(!empty($ratings)): ?>
        <th>Rating</th>
      <?php endif; ?>
    </tr>

    <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <th>
          <?php if($show_links): ?>
            <?php echo e(link_to_route('results.division.round.choir.show', $choir->full_name, [$division, $round, $choir, $access_code])); ?>

          <?php else: ?>
            <?php echo e($choir->full_name); ?>

          <?php endif; ?>
        </th>
        <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

          <?php if($judge->captions->where('id',$caption->id)->count() == 0): ?>
            <td>-</td>
          <?php endif; ?>

          <?php if($judge->captions->where('id',$caption->id)->count() > 0): ?>
            <td>
              <?php $rank = $scoreboard->rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
              <span class="rank score"><?php echo e($rank); ?></span>

              <?php $weighted = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');?>
              <span class="weighted score"><?php echo e($weighted); ?></span>

              <?php $raw = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('score');?>
              <span class="raw score"><?php echo e($raw); ?></span>

            </td>
          <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <td>
          <?php $rank = $scoreboard->rankedScores->total($choir->id, $caption->id);?>
          <span class="rank score"><?php echo e($rank); ?></span>

          <?php $weighted = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');?>
          <span class="weighted score"><?php echo e($weighted); ?></span>

          <?php $raw = $scoreboard->rawScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('score');?>
          <span class="raw score"><?php echo e($raw); ?></span>
        </td>
        <td>
          <?php $rank = $captionTotalRank->where('choir_id' , $choir->id)->pluck('rank')->first();?>
          <span class="rank score"><?php echo e($rank); ?></span>

          <?php $rank = $totalWeightedRank->where('choir_id' , $choir->id)->pluck('rank')->first(); ?>
          <span class="weighted score"><?php echo e($rank); ?></span>

          <?php $rank = $totalRawRank->where('choir_id' , $choir->id)->pluck('rank')->first(); ?>
          <span class="raw score"><?php echo e($rank); ?></span>
        </td>

        <?php if(!empty($ratings)): ?>
          <td></td>
        <?php endif; ?>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


  <tr class="caption-header caption-place">
    <th colspan="30">
      Place
    </th>
  </tr>

  <tr>
    <th></th>

    <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <th>
        <?php if($show_links): ?>
          <?php echo e(link_to_route('results.division.round.judge.show', $judge->full_name, [$division, $round, $judge, $access_code])); ?>

        <?php else: ?>
          <?php echo e($judge->full_name); ?>

        <?php endif; ?>
      </th>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <th>Total</th>
    <th>Place</th>

    <?php if(!empty($ratings)): ?>
      <th>Rating</th>
    <?php endif; ?>
  </tr>

  <?php $totalWeightedRank = $scoreboard->rankedScores->total_weighted_rank(); ?>
  <?php $totalRawRank = $scoreboard->rankedScores->total_raw_rank(); ?>

  <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <th>
        <?php if($show_links): ?>
          <?php echo e(link_to_route('results.division.round.choir.show', $choir->full_name, [$division, $round, $choir, $access_code])); ?>

        <?php else: ?>
          <?php echo e($choir->full_name); ?>

        <?php endif; ?>
      </th>
      <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td>
          <?php $rank = $scoreboard->rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();?>
          <span class="rank score"><?php echo e($rank); ?></span>

          <?php $weightedSubtotal = $scoreboard->weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('weightedScore');?>
          <span class="weighted subtotal score"><?php echo e($weightedSubtotal); ?></span>

          <?php $raw = $scoreboard->rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');?>
          <span class="raw score"><?php echo e($raw); ?></span>

          <?php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');?>
          <span class="penalty weighted score"><?php echo e($penalty); ?></span>

          <?php $weightedTotal = $weightedSubtotal - $penalty; ?>
          <span class="weighted total score"><?php echo e($weightedTotal); ?></span>
        </td>

      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <td>
        <?php $rank = $scoreboard->rankedScores->total($choir->id);?>
        <span class="rank score"><?php echo e($rank); ?></span>

        <?php $weightedSubtotal = $scoreboard->weightedScores->where('choir_id', $choir->id)->sum('weightedScore');?>
        <?php //$weighted = $scoreboard->weightedScores->total($choir->id);?>
        <span class="weighted subtotal score"><?php echo e($weightedSubtotal); ?></span>

        <?php $raw = $scoreboard->rawScores->where('choir_id', $choir->id)->sum('score');?>
        <span class="raw score"><?php echo e($raw); ?></span>

        <?php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 0)->sum('amount');?>
        <span class="penalty weighted overall score"><?php echo e($penalty); ?></span>

        <?php $judgePenalty = $judges->count() * $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');?>
        <span class="penalty weighted judge score"><?php echo e($judgePenalty); ?></span>

        <?php $weightedTotal = $weightedSubtotal - $penalty - $judgePenalty; ?>
        <span class="weighted total score"><?php echo e($weightedTotal); ?></span>

      </td>
      <td>
        <?php $rank = $scoreboard->rankedScores->total_rank()->where('choir_id' , $choir->id)->pluck('rank')->first();?>
        <span class="rank score"><?php echo e($rank); ?></span>

        <?php $rank = $totalWeightedRank->where('choir_id' , $choir->id)->pluck('rank')->first(); ?>
        <span class="weighted score"><?php echo e($rank); ?></span>

        <?php $rank = $totalRawRank->where('choir_id' , $choir->id)->pluck('rank')->first(); ?>
        <span class="raw score"><?php echo e($rank); ?></span>


      </td>

      <?php if(!empty($ratings)): ?>
        <td><?php echo e($ratings->where('choir.id', $choir->id)->pluck('rating.name')->first()); ?></td>
      <?php endif; ?>
    </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</table>
