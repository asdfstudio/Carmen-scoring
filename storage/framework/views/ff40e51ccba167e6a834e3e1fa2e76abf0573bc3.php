<?php if(!$standing->choirs): ?>
  <p>
    No standings to display
  </p>
<?php endif; ?>


<?php
$caption_id = $standing->caption_id;

if($caption_id == NULL)
{
  $sponsors = $division->overall_award_sponsors;
}
elseif($caption_id == 1)
{
  $sponsors = $division->music_award_sponsors;
}
elseif($caption_id == 2)
{
  $sponsors = $division->show_award_sponsors;
}
elseif($caption_id == 3)
{
  $sponsors = $division->combo_award_sponsors;
}
else {
  $sponsors = false;
}

$sponsors = explode(PHP_EOL, $sponsors);

?>

<?php if($standing->choirs): ?>
<ul class="list-group">
  <?php $__currentLoopData = $standing->choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="list-group-item standing">
      <span class="choir"><?php echo e($choir->full_name); ?></span>

      <?php
      $rank_name = false;
      $final_rank = $choir->pivot->final_rank;
      $index = $final_rank - 1;
      $tied = $standing->choirs->where('pivot.final_rank', $choir->pivot->final_rank)->count() > 1 ? true : false;

      $sponsor = array_key_exists($index, $sponsors) ? $sponsors[$index] : false;

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

      <span>Sponsor: <?php echo e($sponsor); ?></span>

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
