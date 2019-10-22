<?php $__env->startSection('breadcrumbs'); ?>
  <?php echo Breadcrumbs::render('organizer.competition.award-schedule.show', $competition, $schedule); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
  <h1><?php echo e($schedule->name); ?></h1>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <ul class="schedule-list announcer-view">
    <?php $__currentLoopData = $schedule->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

      <?php
      $awardWinner = false;
      $sponsor = false;

      if($item->division AND $item->award)
      {
        $awardWinner = $awardWinners->where('division_id', $item->division->id)->where('award_id', $item->award->id)->first();
        $sponsor = $awardWinner->sponsor;
      }
      elseif($item->division)
      {
        if($item->caption)
        {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', $item->caption->id)->first();

          $sponsor = $item->division->awardSettings->where('caption_id', $item->caption->id)->first()->awardSponsor($item->rank);
        }
        else {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', null)->first();
          $sponsor = $item->division->awardSettings->where('caption_id', 0)->first()->awardSponsor($item->rank);
          //dd($item->division->awardSettings->where('caption_id', 0)->first()->awardSponsor($item->rank));
        }


        if($standing AND $standing->choirs)
        {
          $awardWinner = $standing->choirs()->wherePivot('final_rank', $item->rank)->first();
        }
      }

      ?>

      <li class="schedule-item award">

        <div class="award-heading">

          <?php if($item->division): ?>
            <span class="division-name" data-division-id="<?php echo e($item->division->id); ?>"><?php echo e($item->division->name); ?></span>
          <?php endif; ?>

          <?php if($item->round): ?>
            <span class="award-name"><?php echo e($item->round->name); ?> Ratings</span>
          <?php endif; ?>

          <?php if($item->award): ?>
            <span class="award-name"><?php echo e($item->award->name); ?></span>
          <?php endif; ?>

          <?php if($item->caption): ?>
            <span class="caption-name <?php echo e($item->caption->text_css); ?>"><?php echo e($item->caption->name); ?> <?php echo e($item->named_rank); ?></span>
          <?php elseif($item->rank): ?>
            <span class="caption-name caption-overall">Overall <?php echo e($item->named_rank); ?></span>
          <?php endif; ?>

        </div> <!-- end award heading-->


        <?php if($item->round): ?>
          <?php $roundRatings = $ratings->where('round_id', $item->round->id)->first();?>

          <?php if($roundRatings): ?>
            <ul class="list-group">
              <?php $__currentLoopData = $roundRatings['ratings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="list-group-item"><?php echo e($rating['choir']->full_name); ?>: <?php echo e($rating['rating']['name']); ?></li>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
          <?php endif; ?>

        <?php endif; ?>

        <?php if($awardWinner): ?>
          <span class="award-winner">
            <?php if($awardWinner->recipient): ?>
              <span class="award-winner-recipient"><?php echo e($awardWinner->recipient); ?></span>
            <?php endif; ?>

            <?php if($awardWinner->choir): ?>
              <span class="award-winner-choir"><?php echo e($awardWinner->choir->full_name); ?></span>
            <?php endif; ?>

            <?php if($awardWinner->full_name): ?>
              <span class="award-winner-choir"><?php echo e($awardWinner->full_name); ?></span>
            <?php endif; ?>

          </span>
        <?php endif; ?>

        <?php if($sponsor): ?>
          <span class="award-sponsor">Sponsor: <?php echo e($sponsor); ?></span>
        <?php endif; ?>


      </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>


<?php $__env->stopSection(); ?>


<?php $__env->startSection('body-footer'); ?>

  <script>
    $( function() {
      $('li.schedule-item').on('click', function(event) {
        event.preventDefault();
        $(this).toggleClass('done');
      });
    });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>