<?php $__env->startSection('body-header'); ?>
  <style>@import  "/css/director-form.css";</style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content-header'); ?>
  <h1><?php echo e($choir->full_name); ?></h1>

  <ul class="actions-group">
		<li><?php echo e(link_to_route('admin.choir.index','Back to All Choirs', [], ['class' => 'action'])); ?></li>
	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <h2>Add a Director</h2>
  
  <?php echo form($form); ?>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('body-footer'); ?>
  <script src="/js/director-form.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>