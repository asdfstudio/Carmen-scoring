<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.user.index'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Users</h1>

	<ul class="actions-group">
		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('create' , 'App\User')): ?>
		  <li><?php echo e(link_to_route('organizer.user.create', 'Add a user', NULL, ['class' => 'action'])); ?></li>
		<?php endif; ?>
	</ul>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>



	<p>
		This page lists all of the users that are authorized to log in to your organization. Admin users have more rights to create, edit and delete items in your organization. Standard users can view items but have restricted access.
	</p>



  <?php echo $__env->make('user.organizer.table', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>