<?php $__env->startSection('content-header'); ?>
	<h1>Penalties</h1>

	<ul class="actions-group">
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create' , 'App\Penalty')): ?>
		  <li>
				<?php echo e(link_to_route('organizer.penalty.create','Add a penalty',NULL,['class' => 'action'])); ?>

			</li>
		<?php endif; ?>
	</ul>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


	<p>
		This page lists all of your available penalties for your organization. You can choose which penalties (or none at all) to make available in your competition divisions.
	</p>



  <?php echo $__env->make('penalty.organizer.list', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>