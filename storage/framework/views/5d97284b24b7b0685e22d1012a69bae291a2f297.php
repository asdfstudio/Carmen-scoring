<?php $__env->startSection('content'); ?>

	<?php echo Breadcrumbs::render('admin.judge.index'); ?>


  <?php echo $__env->make('judge.table', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>