<?php $__env->startSection('content-header'); ?>
  <h1><?php echo e($choir->full_name); ?></h1>

  <ul class="actions-group">
		<li><?php echo e(link_to_route('admin.choir.show','Back to Choir', [$choir], ['class' => 'action'])); ?></li>
	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <h2>Edit Director</h2>

  <?php echo form($form); ?>


  <h2>Remove director from choir</h2>

  <?php echo form($deleteForm); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>