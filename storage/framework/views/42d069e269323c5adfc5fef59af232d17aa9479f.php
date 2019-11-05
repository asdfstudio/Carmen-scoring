<?php $__env->startSection('breadcrumbs'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Award Settings</h1>

	<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $division)): ?>
		<ul class="actions-group">
			<li><?php echo e(link_to_route('organizer.competition.division.show','Back to Division',[$competition, $division],['class' => 'action'])); ?></li>
		</ul>
	<?php endif; ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <?php echo form($form); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>