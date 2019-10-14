<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.competition.division.show',$competition,$division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<ul class="actions-group mv">
		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('activateScoring', $division)): ?>
			<li><?php echo form($activateScoringForm); ?></li>
		<?php endif; ?>

		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('completeScoring', $division)): ?>
			<li><?php echo form($completeScoringForm); ?></li>

		<?php endif; ?>

		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('finalizeScoring', $division)): ?>
			<li><?php echo form($finalizeScoringForm); ?></li>

		<?php endif; ?>

		<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('update', $division)): ?>
			<li><?php echo e(link_to_route('organizer.competition.division.edit', 'Edit Division', [$competition,$division],['class' => 'action'])); ?></li>
			<li><?php echo e(link_to_route('organizer.competition.division.board', 'Enter Set Up Mode', [$competition,$division],['class' => 'action'])); ?></li>

		<?php endif; ?>

	</ul>

	<div class="clearfix"></div>

	<?php if($divisionRoundIsMissingScores): ?>
		<p class="alert alert-warning">At least one round of this division is currently missing scores. Do not complete the scoring until you have received scores from all judges.</p>
	<?php endif; ?>

	<?php if($division->status_slug() == 'finalized'): ?>
		<div class="alert alert-info">
			<p>Results for this division are available at <?php echo e(link_to_route('results.division.show', NULL, [$division, $division->access_code], ['target' => '_blank'])); ?> </p>
		</div>
	<?php endif; ?>

	<ul class="list-group">
		<li class="list-group-item">
			<h3>Settings</h3>
			<p><?php echo e(link_to_route('organizer.competition.division.settings', 'Manage scoring settings', [$competition, $division])); ?></p>
			<p><?php echo e(link_to_route('organizer.competition.division.award.settings.edit','Edit Award Settings',[$competition, $division])); ?></p>
		</li>
		<li class="list-group-item">
			<h3>Choirs</h3>
			<p><?php echo e(link_to_route('organizer.competition.division.choir.index', 'Manage choirs', [$competition, $division])); ?></p>
		</li>
		<li class="list-group-item">
			<h3>Judges</h3>
			<p><?php echo e(link_to_route('organizer.competition.division.judge.index', 'Manage judges', [$competition, $division])); ?></p>
		</li>
		<li class="list-group-item">
			<h3>Rounds</h3>
			<p><?php echo e(link_to_route('organizer.competition.division.round.index', 'Manage rounds', [$competition, $division])); ?></p>
		</li>
		<li class="list-group-item">
			<h3>Penalties</h3>
			<p><?php echo e(link_to_route('organizer.competition.division.penalty.index', 'Manage penalties', [$competition, $division])); ?></p>
		</li>
		<li class="list-group-item">
			<h3>Awards</h3>
			<p><?php echo e(link_to_route('organizer.competition.division.award.index', 'Manage awards', [$competition, $division])); ?></p>
		</li>
	</ul>

	<div data-tab-id="scoring" class="tab-content">
		<?php echo $__env->make('division.partial.single', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	</div>




  <div class="row">

    <div data-tab-id="rounds" class="tab-content col-xs-12 col-sm-12">

      <h3><?php echo e(link_to_route('organizer.competition.division.round.index','Rounds',[$competition,$division])); ?> (<?php echo e($division->rounds->count()); ?>)</h3>

      <?php echo $__env->make('competition_division_round.organizer.table', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

			<?php echo e(link_to_route('organizer.competition.division.round.create','Add a round',[$competition,$division],['class' => 'btn btn-primary'])); ?>


			<?php echo e(link_to_route('organizer.competition.division.round.setup','Set up rounds',[$competition,$division],['class' => 'btn btn-primary'])); ?>



    </div>

    <div data-tab-id="choirs" class="tab-content col-xs-12 col-sm-12">

      <h3><?php echo e(link_to_route('organizer.competition.division.choir.index','Choirs',[$competition,$division])); ?> (<?php echo e($division->choirs->count()); ?>)</h3>

      <?php echo $__env->make('competition_division_choir.organizer.table', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

      <?php echo e(link_to_route('organizer.competition.division.choir.create','Add a choir',[$competition,$division],['class' => 'btn btn-primary'])); ?>


			<?php echo e(link_to_route('organizer.competition.division.choir.setup','Set up choir',[$competition,$division],['class' => 'btn btn-primary'])); ?>


    </div>

    <div data-tab-id="judges" class="tab-content col-xs-12 col-sm-12">

    	<h3><?php echo e(link_to_route('organizer.competition.division.judge.index','Judges',[$competition,$division])); ?> (<?php echo e($division->judges->count()); ?>)</h3>

    	<?php echo $__env->make('competition_division_judge.organizer.table',['judges' => $division->judges], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

      <?php echo e(link_to_route('organizer.competition.division.judge.create','Add a judge',[$competition,$division],['class' => 'btn btn-primary'])); ?>


			<?php echo e(link_to_route('organizer.competition.division.judge.setup','Set up judges',[$competition,$division],['class' => 'btn btn-primary'])); ?>


    </div>


		<div data-tab-id="awards" class="tab-content col-xs-12 col-sm-12">

    	<h3><?php echo e(link_to_route('organizer.competition.division.award.index', 'Awards', [$competition,$division])); ?> (<?php echo e($division->awards->count()); ?>)</h3>

    	<?php echo $__env->make('award.organizer.list', ['awards' => $division->awards], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    </div>

		<div data-tab-id="penalties" class="tab-content col-xs-12 col-sm-12">

    	<h3><?php echo e(link_to_route('organizer.competition.division.penalty.index','Penalties', [$competition,$division])); ?> (<?php echo e($division->penalties->count()); ?>)</h3>

    	<?php echo $__env->make('penalty.organizer.list', ['penalties' => $division->penalties], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    </div>

  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>