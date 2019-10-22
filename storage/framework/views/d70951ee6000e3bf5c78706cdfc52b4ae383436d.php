<?php $__env->startSection('division_navigation_bar'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
  <h1><?php echo e($choir->full_name); ?></h1>

  <ul class="actions-group">
    <li>
      <?php echo e(link_to_route('judge.round.scores.summary', 'All Choirs', [$competition, $division, $round], ['class' => 'action'])); ?>

    </li>
  </ul>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>


  <?php if($round->is_scoring_active AND $judge->id == Auth::user()->person_id): ?>

  	<?php echo $__env->make('scores.forms.choir_raw_alt',['division' => $round->division, 'judge' => $round->division->judges->first()], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php else: ?>

  	<?php echo $__env->make('scores.choir_judge_raw',['division' => $round->division], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>