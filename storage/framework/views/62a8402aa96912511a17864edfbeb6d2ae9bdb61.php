<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.organization.show'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Organization Details</h1>

	<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('update',$organization)): ?>
		<?php echo e(link_to_route('organizer.organization.edit','Edit organization', [], ['class' => 'action'])); ?>

	<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>



		<h2><?php echo e($organization->name); ?></h2>

		<h3>Location</h3>

		<?php echo $__env->make('place.show', ['place' => $organization->place], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>