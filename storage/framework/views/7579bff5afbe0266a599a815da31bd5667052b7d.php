<?php if(!$standing->choirs): ?>
  <p>
    No standings to display
  </p>
<?php endif; ?>


<?php
$captionId = $standing->caption_id ? $standing->caption_id : 0;
?>

<?php if($standing->choirs): ?>
<ul class="list-group">
  <?php $__currentLoopData = $standing->choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="list-group-item standing">
      <span class="choir"><?php echo e($choir->full_name); ?></span>

      <?php
      $rank_name = false;
      $final_rank = $choir->pivot->final_rank;
      //$index = $final_rank - 1;
      $tied = $standing->choirs->where('pivot.final_rank', $choir->pivot->final_rank)->count() > 1 ? true : false;

      //$sponsor = array_key_exists($index, $sponsors) ? $sponsors[$index] : false;

      $awardSetting = $division->awardSettings->where('caption_id', $captionId)->first();

      if ($awardSetting) {
        $sponsor = $awardSetting->awardSponsor($final_rank);
      } else {
        $sponsor = false;
      }


      if($division->competition->use_runner_up_names)
      {
        if($final_rank == 1)
        {
          $rank_name = 'Champion';
        }
        else
        {
          $runner_up_number = $final_rank - 1;
          $rank_name = ordinal($runner_up_number) . ' Runner Up';
        }
      }
      else {
        $rank_name = ordinal($final_rank);
      }
      ?>

      <?php if($sponsor AND $showSponsor): ?>
        <span>Sponsored by: <?php echo e($sponsor); ?></span>
      <?php endif; ?>

      <div class="details">

        <span class="final_rank ceremony rank-<?php echo e($choir->pivot->final_rank); ?>">
          <?php echo e($rank_name); ?>

        </span>
        <?php if($tied): ?>
          <span class="tied">tied</span>
        <?php endif; ?>

      </div>

    </li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
