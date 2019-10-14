<?php $__env->startSection('content-header'); ?>
  <h1><?php echo e($choir->full_name); ?></h1>

  <ul class="actions-group">
		<li><?php echo e(link_to_route('admin.choir.index','Back to All Choirs', [], ['class' => 'action'])); ?></li>
	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <h2>Director(s)</h2>

  <?php echo $__env->make('person.partial.list', ['people' => $choir->directors], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php echo e(link_to_route('admin.choir.director.create','Add a director', [$choir], ['class' => 'action'])); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>