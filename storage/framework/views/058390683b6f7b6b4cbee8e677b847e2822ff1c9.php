<?php $__env->startSection('content-header'); ?>
  <h1>Organizations</h1>

  <?php echo e(link_to_route('admin.organization.create', 'Add an organization', [] ,['class' => 'action'])); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

  <?php echo $__env->make('organization.admin.list', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>