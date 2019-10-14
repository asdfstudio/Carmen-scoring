<?php $__env->startSection('content'); ?>



  <h1>Active and Upcoming Competitions</h1>

  <?php echo $__env->make('competition.judge.list', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


  <h2>Archived Competitions</h2>
  <?php echo $__env->make('competition.judge.list',['competitions' => $archivedCompetitions], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>