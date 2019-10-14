<?php $__env->startSection('breadcrumbs'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Users</h1>

	<ul class="actions-group">
		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('create' , 'App\User')): ?>
		  <li><?php echo e(link_to_route('admin.user.create', 'Add a user', [], ['class' => 'action'])); ?></li>
		<?php endif; ?>
	</ul>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('user.admin.table', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>