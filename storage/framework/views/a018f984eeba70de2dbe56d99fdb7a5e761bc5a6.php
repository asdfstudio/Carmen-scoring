<?php $__env->startSection('content-header'); ?>
  <h1>Raw Score Log - <?php echo e($date); ?></h1>

  <ul class="actions-group">
    <li><?php echo e(link_to_route('admin.raw-score-log.index', 'Back to logs', [], ['class' => 'action'])); ?></li>
  </ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <pre><?php echo e($fileContents); ?></pre>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>