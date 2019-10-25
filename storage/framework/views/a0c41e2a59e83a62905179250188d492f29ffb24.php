
<table class="table table-striped table-bordered scoreboard toggle-scores rank">
  <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <?php
      if($division->caption_weighting_id === 1){
        $captionTotalRank = $rankedScores->total_weighted_rank($caption->id);
      } else {
        $captionTotalRank = $rankedScores->total_raw_rank($caption->id);
      }
      $election_key = $division->caption_weighting_id === 1 ? 'caption_'.$caption->id.'_weighted' : 'caption_'.$caption->id;
    ?>

    <tr class="caption-header <?php echo e($caption->background_css); ?>">
      <th colspan="30">
        <?php echo e($caption->name); ?> <?php echo e($election_key); ?>

      </th>
    </tr>

    <tr class="align-bottom">
      <th></th>

      <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <th class="sideways-header">
          <?php echo e(link_to_route('organizer.competition.division.round.choir.show',$choir->name,[$round->division->competition,$round->division,$round,$choir])); ?>

        </th>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      
      <th>Sum</th>
      
      <th>Rank</th>

      <?php if(!empty($ratings)): ?>
        <th>Rating</th>
      <?php endif; ?>
    </tr>

    <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <th>
          <?php echo e(link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir])); ?>

        </th>
        
        <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir_comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <td>
            <?php echo e($rankedScores->pairwise_bit($election_key, $choir->id, $choir_comp->id)); ?>

          </td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <td>
          <?php echo e($rankedScores->pairwise_bit_sum($election_key, $choir->id)); ?>

        </td>
        
        <td>
          <?php
            $rank = $captionTotalRank->where('choir_id', $choir->id)->pluck('rank')->first();
            $tied = !empty($captionTotalRank->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
          ?>
          <span class="rank score <?php echo e($tied); ?>"><?php echo e($rank); ?></span>
        </td>

        <?php if(!empty($ratings)): ?>
          <td></td>
        <?php endif; ?>
      </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


  <?php
    if($division->caption_weighting_id === 1){
      $captionTotalRank = $rankedScores->total_weighted_rank();
    } else {
      $captionTotalRank = $rankedScores->total_raw_rank();
    }
    $election_key = $division->caption_weighting_id === 1 ? 'overall_weighted' : 'overall';
  ?>

  <tr class="caption-header caption-place">
    <th colspan="30">
      Place <?php echo e($election_key); ?>

    </th>
  </tr>

  <tr class="align-bottom">
    <th></th>

    <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <th class="sideways-header">
        <?php echo e(link_to_route('organizer.competition.division.round.choir.show',$choir->name,[$round->division->competition,$round->division,$round,$choir])); ?>

      </th>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <th>Sum</th>

    <th>Rank</th>

    <?php if(!empty($ratings)): ?>
      <th>Rating</th>
    <?php endif; ?>
  </tr>

  <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
      <th>
        <?php echo e(link_to_route('organizer.competition.division.round.choir.show',$choir->full_name,[$round->division->competition,$round->division,$round,$choir])); ?>

      </th>
      <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir_comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <td><?php echo e($rankedScores->pairwise_bit($election_key, $choir->id, $choir_comp->id)); ?></td>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <td>
        <?php echo e($rankedScores->pairwise_bit_sum($election_key, $choir->id)); ?>

      </td>

      <td>
        <?php
          $rank = $captionTotalRank->where('choir_id' , $choir->id)->pluck('rank')->first();
          $tied = !empty($captionTotalRank->where('choir_id', $choir->id)->pluck('tied')->first()) ? 'tied' : '';
        ?>
        <span class="rank score <?php echo e($tied); ?>"><?php echo e($rank); ?></span>
      </td>

      <?php if(!empty($ratings)): ?>
        <td><?php echo e($ratings->where('choir.id', $choir->id)->pluck('rating.name')->first()); ?></td>
      <?php endif; ?>
    </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</table>
