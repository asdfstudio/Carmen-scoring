<?php $__env->startSection('title'); ?>
  <?php echo e($competition->name); ?> | ##parent-placeholder-3c6de1b7dd91465d437ef415f94f36afc1fbc8a8##
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumbs'); ?>
  <?php echo Breadcrumbs::render('organizer.competition.show',$competition); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
  <h1>Competition Details</h1>

  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $competition)): ?>
    <?php echo e(link_to_route('organizer.competition.edit', 'Edit Competition', [$competition], ['class' => 'action'])); ?>

  <?php endif; ?>


<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>

  <ul class="actions-group mv">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('activateScoring', $competition)): ?>
      <li><?php echo form($activateScoringForm); ?></li>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('archiveCompetition', $competition)): ?>
      <li><?php echo form($archiveCompetitionForm); ?></li>
    <?php endif; ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('completeScoring', $competition)): ?>
      <li><?php echo form($completeScoringForm); ?></li>
    <?php endif; ?>
  </ul>

  <h3>Competition Results</h3>

  <ul class="list-group">
    <li class="list-group-item">Results URL: <?php echo e(link_to($competition->results_url)); ?></li>
    <li class="list-group-item">Access Code: <?php echo e($competition->access_code); ?></li>
  </ul>


  <h3>Manage Divisions</h3>
  <p>Divisions are used to organize your competition and consist of choirs, judges, scoring settings and more.</p>

  <p><?php echo e(link_to_route('organizer.competition.division.index','Manage your divisions',[$competition], ['class' => 'action'])); ?></p>

  <?php if($competition->divisions->count() > 0): ?>

    <?php echo $__env->make('division.organizer.list',['divisions' => $competition->divisions], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php else: ?>
    <p><?php echo e(link_to_route('organizer.competition.division.create','Create your first division',[$competition])); ?></p>
  <?php endif; ?>

  <h3>Manage Solo Divisions</h3>

  <?php if($competition->soloDivisions->count() > 0): ?>
    <p><?php echo e(link_to_route('organizer.competition.solo-division.create','Create a solo division',[$competition], ['class' => 'action'])); ?></p>

    <?php echo $__env->make('solo-division.organizer.list',['soloDivisions' => $competition->soloDivisions], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php else: ?>
    <p><?php echo e(link_to_route('organizer.competition.solo-division.create','Create your first solo division',[$competition])); ?></p>
  <?php endif; ?>


  <h3>Manage Schedules</h3>

  <p>Set the performance order for your competition. Do this after you have created all of your divisions, rounds and choirs.</p>

  <p><?php echo e(link_to_route('organizer.competition.schedule.create','Add a performance schedule',[$competition], ['class' => 'action'])); ?></p>

  <?php echo $__env->make('schedule.organizer.table', ['schedules' => $competition->schedules], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <h3>Manage Award Ceremony Schedules</h3>

  <p>Set the schedule for your award ceremonies.</p>

  <p><?php echo e(link_to_route('organizer.competition.award-schedule.create','Add an award ceremony schedule',[$competition], ['class' => 'action'])); ?></p>

  <?php echo $__env->make('award-schedule.organizer.table', ['schedules' => $competition->awardSchedules], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>


  <h3>Feedback Links</h3>
  <p>View the URLs where choir directors can view feedback from judges.</p>
  <p><?php echo e(link_to_route('organizer.competition.comment-links.index','View feedback links',[$competition], ['class' => 'action'])); ?></p>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>