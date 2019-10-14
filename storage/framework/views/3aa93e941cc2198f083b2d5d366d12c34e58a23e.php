<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.competition.index'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>Competitions</h1>

	<ul class="actions-group">
		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('create','App\Competition')): ?>
			<li><?php echo e(link_to_route('organizer.competition.create','Add a competition',NULL,['class' => 'action'])); ?></li>
		<?php endif; ?>
	</ul>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>




	<p>
		Below you will find a list of your active and archived competitions. Active competitions are those that are upcoming or in-progress. Archived competitions are those that have been completed.
	</p>
  <h2>Active Competitions</h2>



  <?php echo $__env->make('competition.organizer.table', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


  <h2>Archived Competitions</h2>
  <?php echo $__env->make('competition.organizer.table',['competitions' => $archivedCompetitions], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>