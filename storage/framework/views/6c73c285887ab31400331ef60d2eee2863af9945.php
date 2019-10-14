<?php $__env->startSection('content-header'); ?>
  <h1>Captions</h1>

	<?php echo e(link_to_route('admin.caption.create', 'Add a caption', [], ['class' => 'action'])); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php echo $__env->make('caption.admin.list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>