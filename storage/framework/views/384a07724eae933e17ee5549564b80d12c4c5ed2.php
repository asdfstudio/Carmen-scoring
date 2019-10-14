<?php $__env->startSection('content-header'); ?>
  <h1>Criteria</h1>

	<?php echo e(link_to_route('admin.criteria.create', 'Add a criterion', [], ['class' => 'action'])); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <h2><?php echo e($caption->name); ?></h2>
    <?php $filteredCriteria = $criteria->where('caption_id', $caption->id); ?>
    <?php echo $__env->make('criteria.admin.list', ['criteria' => $filteredCriteria], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>