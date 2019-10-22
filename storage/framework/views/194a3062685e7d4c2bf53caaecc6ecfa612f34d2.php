<?php $__env->startSection('content-header'); ?>
	<h1>Final Standings</h1>

	<ul class="actions-group">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show', $division)): ?>
      <li>
				<?php echo e(link_to_route('organizer.competition.division.show', 'Back to Division', [$division->competition,$division], ['class' => 'action'])); ?>

			</li>
    <?php endif; ?>


	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<?php $__currentLoopData = $division->standings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $standing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<div class="standing-container">


			<?php if($standing->caption_id == NULL): ?>
				<div class="content-subheader caption">
					<h2>Overall Standings</h2>
			<?php else: ?>
				<div class="content-subheader caption <?php echo e($standing->caption->background_css); ?>">
					<h2><?php echo e($standing->caption->name); ?> Standings</h2>
			<?php endif; ?>

				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $standing)): ?>

					<?php echo e(link_to_route('organizer.competition.division.standing.edit', 'Modify Standings', [$division->competition, $division, $standing], ['class' => 'action'])); ?>


		    <?php endif; ?>
			</div>



			<?php if($standing == false): ?>
		    <p>
		      There are no final standings yet.
		    </p>
		  <?php endif; ?>

			<?php if($standing): ?>
		    <?php if($standing->is_consensus_scoring): ?>
		      <p class="alert alert-warning">
		        Consensus scoring is used for this division.
		      </p>
		    <?php endif; ?>

		  	<?php echo $__env->make('standing.list', ['standing' => $standing], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		  <?php endif; ?>



		</div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>





<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>