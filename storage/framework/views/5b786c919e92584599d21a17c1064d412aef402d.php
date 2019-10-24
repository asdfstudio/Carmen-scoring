<?php $__env->startSection('division_navigation_bar'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <h3>Scoring Mode</h3>

  <h4>Select Round to Score</h4>

  <?php echo $__env->make('competition_division_round.judge.list', ['rounds' => $division->rounds], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>