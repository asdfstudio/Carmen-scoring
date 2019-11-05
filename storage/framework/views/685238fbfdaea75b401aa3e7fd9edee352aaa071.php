<?php $__env->startSection('content-header'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <?php if($authorized): ?>
    <?php echo Breadcrumbs::render('results.competition.show-public', $competition); ?>


    <h2><?php echo e($competition->name); ?> Results By Division</h2>

    <?php if($competition->divisions->count() == 0): ?>
      <p>There are currently no divisions with published results. Please check back again shortly.</p>
    <?php endif; ?>


    <?php if($competition->divisions->count() > 0): ?>
      <ul class="list-group">
        <?php $__currentLoopData = $competition->divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="list-group-item"><?php echo e(link_to_route('results.division.show', $div->name, [$div , $div->access_code])); ?></li>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    <?php endif; ?>
  <?php endif; ?>


  <?php if(!$authorized): ?>
    <h2><?php echo e($competition->name); ?> Results</h2>

    <?php echo $__env->make('results/competition/access_code_form', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endif; ?>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public_results', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>