<?php $__env->startSection('content-header'); ?>


	<?php if($standing->caption_id == NULL): ?>
		<h1>Edit Overall Standings</h1>
	<?php else: ?>
		<h1>Edit <?php echo e($standing->caption->name); ?> Standings</h1>
	<?php endif; ?>

	<ul class="actions-group">
			<li>
				<?php echo e(link_to_route('organizer.competition.division.show', 'Back to Division', [$division->competition,$division], ['class' => 'action'])); ?>

			</li>

      <li>
				<?php echo e(link_to_route('organizer.competition.division.standing.show', 'Back to Standings', [$division->competition, $division], ['class' => 'action'])); ?>

			</li>
	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<p>
		This page allows you to modify the final standings for this division. It's purpose is to allow for manually overriding aggregate scores. It should be used for consensus scoring.
	</p>

	<?php echo $__env->make('standing.edit', ['standing' => $standing], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>