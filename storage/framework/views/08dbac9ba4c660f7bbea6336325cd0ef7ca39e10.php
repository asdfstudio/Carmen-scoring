<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('results.division.show-public', $division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

		<h2 id="awards">Awards</h2>

		<div class="individual-awards-container">
		  <h3>Individual Awards</h3>

		  <?php echo $__env->make('award.organizer.ceremony_list', ['awards' => $division->awards], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		</div>

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

				$standing->choirs = $standing->choirs->where('pivot.final_rank', '<=', $limit)->reverse();
				?>
			<?php endif; ?>

			<?php if($standing->choirs->count() > 0): ?>
				<div class="standing-container">

					<?php if($standing->caption_id == NULL): ?>
						<div class="content-subheader caption">
							<h3>Overall Standings</h3>
					<?php else: ?>
						<div class="content-subheader caption <?php echo e($standing->caption->background_css); ?>">
							<h3><?php echo e($standing->caption->name); ?> Standings</h3>
					<?php endif; ?>
					</div>

					<?php echo $__env->make('standing.public_list', ['standing' => $standing, 'showSponsor' => true], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

				</div>
			<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public_results', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>