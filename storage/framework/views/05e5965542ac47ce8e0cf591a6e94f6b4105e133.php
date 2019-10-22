<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('organizer.competition.division.round.show',$round->division->competition,$round->division,$round); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1><?php echo e($round->name); ?></h1>

	<ul class="actions-group">

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('showAll','App\Round')): ?>
			<li><?php echo e(link_to_route('organizer.competition.division.round.index', 'Back to all Rounds', [$division->competition,$division], ['class' => 'action'])); ?></li>
		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $round)): ?>
			<li>
				<?php echo e(link_to_route('organizer.competition.division.round.edit', 'Edit', [$division->competition,$division,$round], ['class' => 'action'])); ?>

			</li>
		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('activateScoring', $round)): ?>
			<li>
				<?php echo form($activateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]); ?>

			</li>
		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deactivateScoring', $round)): ?>
			<li>
				<?php echo form($deactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]); ?>

			</li>
		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('completeScoring', $round)): ?>
			<li>
				<?php echo form($completeScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]); ?>

			</li>
		<?php endif; ?>

		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reactivateScoring', $round)): ?>
			<li>
				<?php echo form($reactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id])]); ?>

			</li>
		<?php endif; ?>

	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	##parent-placeholder-040f06fd774092478d450774f5ba30c5da78acc8##

	<?php if($roundIsMissingScores): ?>
		<p class="alert alert-warning">This round is currently missing scores. Do not complete the scoring until you have received scores from all judges.</p>
	<?php endif; ?>
  
  Scoring Method: <?php echo $division->scoring_method_id; ?><br>
  Weighting: <?php echo $division->caption_weighting_id; ?><br>
  
  
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
    
  	<?php echo $__env->make('scores.organizer.composite_condorcet',['choirs' => $choirs, 'judges' => $division->judges], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php else: ?>
    
  	<?php echo $__env->make('scores.organizer.composite',['choirs' => $choirs, 'judges' => $division->judges], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endif; ?>

  </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>