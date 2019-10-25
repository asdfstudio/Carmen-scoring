<?php if($division->scoring_method_id !== 5): ?>
  <?php
    $composite_table_class = 'weighted raw';
    if($division->scoring_method_id !== 3 && $division->scoring_method_id !== 4){
      $composite_table_class = $composite_table_class . ' rank';
    }
  ?>
<?php endif; ?>
<table class="table table-striped table-bordered scoreboard toggle-scores <?php echo e($composite_table_class); ?>">
  <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <?php
      $captionTotalRank = $rankedScores->total_rank($caption->id);
      $totalWeightedRank = $rankedScores->total_weighted_rank($caption->id);
      $totalRawRank = $rankedScores->total_raw_rank($caption->id);
    ?>

    <tr class="caption-header <?php echo e($caption->background_css); ?>">
      <th colspan="30">
        <?php echo e($caption->name); ?>

      </th>
    </tr>

    <tr>
      <th></th>

      <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <th>
          <?php echo e($judge->full_name); ?>

        </th>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      
      
      <?php if($division->scoring_method_id !== 5): ?>
      <th>Total</th>
      <?php endif; ?>
      
      <th>Place</th>

      <?php if(!empty($ratings)): ?>
        <th>Rating</th>
      <?php endif; ?>
    </tr>

    <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <th>
          <?php echo e(link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir])); ?>

        </th>
        <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

          <?php if($judge->captions->where('id',$caption->id)->count() == 0): ?>
            <td>-</td>
          <?php endif; ?>

          <?php if($judge->captions->where('id',$caption->id)->count() > 0): ?>
            <td>
              <?php
                $rank = $rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('rank')->first();
                $tied = !empty($rankedScores->rank($judge->id, $caption->id)->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
              ?>
              <span class="rank score <?php echo e($tied); ?>"><?php echo e($rank); ?></span>

              <?php $weighted = $weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');?>
              <span class="weighted score"><?php echo e($weighted); ?></span>

              <?php $raw = $rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->where('criterion_caption_id', $caption->id)->sum('score');?>
              <span class="raw score"><?php echo e($raw); ?></span>

            </td>
          <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        
        <?php if($division->scoring_method_id !== 5): ?>
        <td>
          <?php $rank = $rankedScores->total($choir->id, $caption->id);?>
          <span class="rank score"><?php echo e($rank); ?></span>

          <?php $weighted = $weightedScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('weightedScore');?>
          <span class="weighted score"><?php echo e($weighted); ?></span>

          <?php $raw = $rawScores->where('choir_id', $choir->id)->where('criterion_caption_id', $caption->id)->sum('score');?>
          <span class="raw score"><?php echo e($raw); ?></span>
        </td>
        <?php endif; ?>
        
        <td>
          <?php
            $rank = $captionTotalRank->where('choir_id' , $choir->id)->pluck('rank')->first();
            $tied = !empty($captionTotalRank->where('choir_id' , $choir->id)->pluck('tied')->first()) ? 'tied' : '';
          ?>
          <span class="rank score <?php echo e($tied); ?>"><?php echo e($rank); ?></span>

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
        <?php echo e($judge->full_name); ?>

      </th>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
    <?php if($division->scoring_method_id !== 5): ?>
    <th>Total</th>
    <?php endif; ?>
    
    <th>Place</th>

    <?php if(!empty($ratings)): ?>
      <th>Rating</th>
    <?php endif; ?>
  </tr>

  <?php
    $totalWeightedRank = $rankedScores->total_weighted_rank();
    $totalRawRank = $rankedScores->total_raw_rank();
  ?>

  <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <th>
        <?php echo e(link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir])); ?>

      </th>
      <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td>
          <?php
            $rank = $rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('rank')->first();
            $tied = !empty($rankedScores->rank($judge->id)->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
          ?>
          <span class="rank score <?php echo e($tied); ?>"><?php echo e($rank); ?></span>

          <?php $weightedSubtotal = $weightedScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('weightedScore');?>
          <span class="weighted subtotal score"><?php echo e($weightedSubtotal); ?></span>

          <?php $raw = $rawScores->where('choir_id', $choir->id)->where('judge_id', $judge->id)->sum('score');?>
          <span class="raw score"><?php echo e($raw); ?></span>

          <?php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');?>
          <span class="penalty weighted score"><?php echo e($penalty); ?></span>

          <?php $weightedTotal = $weightedSubtotal - $penalty; ?>
          <span class="weighted total score"><?php echo e($weightedTotal); ?></span>
        </td>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      
      
      <?php if($division->scoring_method_id !== 5): ?>
      <td>
        <?php $rank = $rankedScores->total($choir->id);?>
        <span class="rank score"><?php echo e($rank); ?></span>

        <?php $weightedSubtotal = $weightedScores->where('choir_id', $choir->id)->sum('weightedScore');?>
        <?php //$weighted = $weightedScores->total($choir->id);?>
        <span class="weighted subtotal score"><?php echo e($weightedSubtotal); ?></span>

        <?php $raw = $rawScores->where('choir_id', $choir->id)->sum('score');?>
        <span class="raw score"><?php echo e($raw); ?></span>

        <?php $penalty = $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 0)->sum('amount');?>
        <span class="penalty weighted overall score"><?php echo e($penalty); ?></span>

        <?php $judgePenalty = $judges->count() * $scoreboard->penalties->where('choir_id', $choir->id)->where('apply_per_judge', 1)->sum('amount');?>
        <span class="penalty weighted judge score"><?php echo e($judgePenalty); ?></span>

        <?php $weightedTotal = $weightedSubtotal - $penalty - $judgePenalty; ?>
        <span class="weighted total score"><?php echo e($weightedTotal); ?></span>

      </td>
      <?php endif; ?>
      
      <td>
        <?php
          $rank = $rankedScores->total_rank()->where('choir_id' , $choir->id)->pluck('rank')->first();
          $tied = !empty($rankedScores->total_rank()->where('choir_id' , $choir->id)->pluck('tied')->first()) ? 'tied' : '';
        ?>
        <span class="rank score <?php echo e($tied); ?>"><?php echo e($rank); ?></span>

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
