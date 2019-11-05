<?php $__env->startSection('breadcrumbs'); ?>
  <?php echo Breadcrumbs::render('organizer.competition.award-schedule.show', $competition, $schedule); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
  <h1><?php echo e($schedule->name); ?></h1>

  <ul class="actions-group">
    <li><?php echo e(link_to_route('organizer.competition.award-schedule.edit', 'Edit Name', [$competition,$schedule], ['class' => 'action'])); ?></li>
    <li><?php echo e(link_to_route('organizer.competition.award-schedule.builder', 'Build Schedule', [$competition,$schedule], ['class' => 'action'])); ?></li>
    <li><?php echo e(link_to_route('organizer.competition.award-schedule.show-announcer', 'Announcer View', [$competition, $schedule], ['class' => 'action'])); ?></li>
    <li><?php echo form($deleteForm); ?></li>
	</ul>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <ul class="schedule-list">
    <?php $__currentLoopData = $schedule->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

      <?php
      $awardWinner = false;

      if($item->division AND $item->award)
      {
        $awardWinner = $awardWinners->where('division_id', $item->division->id)->where('award_id', $item->award->id)->first();
      }
      elseif($item->division)
      {
        if($item->caption)
        {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', $item->caption->id)->first();
        }
        else {
          $standing = $standings->where('division_id', $item->division->id)->where('caption_id', NULL)->first();
        }


        if($standing AND $standing->choirs)
        {
          $awardWinner = $standing->choirs()->wherePivot('final_rank', $item->rank)->first();
        }

      }


      ?>

      <li class="schedule-item award">
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
        <?php elseif($item->named_rank): ?>
          <span class="caption-name caption-overall">Overall <?php echo e($item->named_rank); ?> </span>
        <?php endif; ?>

        <?php if($awardWinner): ?>
          <span class="award-winner pull-right">
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

          <?php if($awardWinner->sponsor): ?>
            <!--<span class="award-sponsor"><?php echo e($awardWinner->sponsor); ?></span>-->
          <?php endif; ?>
        <?php endif; ?>
      </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>