<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('results.division.show-public', $division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<h2><?php echo e($round->name); ?></h2>

	<?php if($show_links): ?>
		<div class="alert alert-info">
			<h3>Participants, You can view full scores</h3>
			<p>Click on the name of a <strong>choir</strong> or <strong>judge</strong> to view their score details.</p>
		</div>
	<?php endif; ?>

  
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

  <?php echo $__env->make('scores.public.composite',['choirs' => $choirs, 'judges' => $judges, 'scoreboard' => $scoreboard], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public_results', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>