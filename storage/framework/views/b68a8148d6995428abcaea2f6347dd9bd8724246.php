<?php $__env->startSection('content'); ?>

    <h3>Group Divisions</h3>
    <?php if($competition->divisions->count() > 0): ?>
      <?php echo $__env->make('division.judge.list',['divisions' => $competition->divisions], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php else: ?>
      <p>There are no divisions.</p>
    <?php endif; ?>


    <h3>Solo Divisions</h3>

    <?php if($competition->soloDivisions->count() > 0): ?>
      <?php echo $__env->make('solo-division.judge.list',['soloDivisions' => $competition->soloDivisions], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php else: ?>
      <p>There are no solo divisions.</p>
    <?php endif; ?>

    <?php echo e(link_to_route('judge.competition.schedule.index', 'Show Schedules', [$competition])); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>