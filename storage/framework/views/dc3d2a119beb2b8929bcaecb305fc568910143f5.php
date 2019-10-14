<?php $__env->startSection('content-header'); ?>
  <h1>Create a school</h1>

	<?php echo e(link_to_route('admin.school.index', 'Back to schools', [], ['class' => 'action'])); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
		<?php echo form($form); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>