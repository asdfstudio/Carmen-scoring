<?php $__env->startSection('content-header'); ?>
	<h1>Award Ceremony</h1>

	<ul class="actions-group">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', $division)): ?>
      <li>
				<?php echo e(link_to_route('organizer.competition.division.show', 'Back to Division', [$division->competition,$division], ['class' => 'action'])); ?>

			</li>
    <?php endif; ?>


	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <div class="individual-awards-container">
    <h2>Individual Awards</h2>

    <?php echo $__env->make('award.organizer.ceremony_list', ['awards' => $division->awards], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  </div>

	<div class="caption-awards-container">
    <?php //dd($division->standings); ?>
		<?php $__currentLoopData = $division->standings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $standing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

			<?php if($standing): ?>
				<?php
				if($standing->caption_id == NULL)
				{
					$awardSetting = $division->awardSettings->where('caption_id', 0)->first();
				}
				else
				{
					$awardSetting = $division->awardSettings->where('caption_id', $standing->caption_id)->first();
				}

				if ($awardSetting) {
					$limit = $awardSetting->award_count;
				} else {
					$limit = 0;
				}

				if($limit == 0) continue;

				$standing->choirs = $standing->choirs->where('pivot.final_rank', '<=', $limit)->reverse();
				?>

			<?php endif; ?>

			<div class="standing-container">

				<div class="content-subheader caption <?php echo e($standing->caption_slug); ?>">
					<?php if($standing->caption_id == NULL): ?>
						<h2>Overall Standings</h2>
					<?php else: ?>
						<h2><?php echo e($standing->caption->name); ?> Standings</h2>
					<?php endif; ?>
				</div>



				<?php if($standing == false): ?>
			    <p>
			      There are no final standings yet.
			    </p>
			  <?php endif; ?>

				<?php echo $__env->make('standing.ceremony_list', ['standing' => $standing], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>



			</div>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

	</div>


  <div class="alert alert-info">
    <p>When the Award Ceremony is over, remember to finalize/publish the results of this division. This will allow particants, judges and the general public to view the results.</p>
    <p><?php echo e(link_to_route('organizer.competition.division.show', 'Go to Division', [$division->competition, $division])); ?></p>
  </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>