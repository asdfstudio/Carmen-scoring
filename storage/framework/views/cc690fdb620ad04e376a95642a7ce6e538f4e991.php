<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.competition.division.round.show',$round->division->competition,$round->division,$round); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1><?php echo e($round->division->name); ?>, <?php echo e($round->name); ?> Sources</h1>

	<ul class="actions-group">

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('showAll','App\Round')): ?>
			<li><?php echo e(link_to_route('organizer.competition.division.round.index', 'Back to all Rounds', [$division->competition,$division], ['class' => 'action'])); ?></li>
		<?php endif; ?>

	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

  
  <?php if($division->scoring_method_id === 1 && $division->caption_weighting_id === 2): ?>
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  <?php endif; ?>
  
  
  <?php if($division->scoring_method_id === 1 && $division->caption_weighting_id === 1): ?>
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#weighted" data-score-view="weighted">Weighted</a>
        <span>(division scoring method, <?php echo e($division->captionWeighting->name); ?>)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  <?php endif; ?>
  
  
  <?php if($division->scoring_method_id > 1 && $division->caption_weighting_id === 2): ?>
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="rank">Rankings</a>
        <span>(division scoring method)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  <?php endif; ?>

  
  <?php if($division->scoring_method_id > 1 && $division->caption_weighting_id === 1): ?>
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="rank">Rankings</a>
        <span>(division scoring method)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#weighted" data-score-view="weighted">Weighted</a>
        <span>(<?php echo e($division->captionWeighting->name); ?>)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  <?php endif; ?>
  
  
  <?php if($division->scoring_method_id === 3 || $division->scoring_method_id === 4): ?>
  	<?php echo $__env->make('scores.organizer.ranked_condorcet',['choirs' => $choirs, 'judges' => $division->judges], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endif; ?>

	<?php echo $__env->make('scores.organizer.composite',['choirs' => $choirs, 'judges' => $judges], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>