<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.organization.edit', $organization); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Edit Organization Details</h1>

	<?php echo e(link_to_route('organizer.organization.show','Back to organization', [], ['class' => 'action'])); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>





		<?php echo form($form); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>