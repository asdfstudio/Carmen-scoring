<?php $__env->startSection('content-header'); ?>
  <h1>Create a choir</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

		<?php echo form($form); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>