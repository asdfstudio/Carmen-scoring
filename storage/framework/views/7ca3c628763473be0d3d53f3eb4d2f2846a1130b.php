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

				<ul class="list-group horizontal">
					<li class="list-group-item">
						<?php $active = $division->scoringMethod->slug == 'ranked' ? 'active division-scoring-method' : false; ?>
						<a class="score-view-toggle <?php echo e($active); ?>" href="#rankings" data-score-view="rank">Rankings</a>

						<?php if($active): ?>
							<span>(division scoring method)</span>
						<?php endif; ?>
					</li>
					<li class="list-group-item">
						<?php $active = $division->scoringMethod->slug == 'raw' ? 'active division-scoring-method' : false; ?>
						<a class="score-view-toggle <?php echo e($active); ?>" href="#weighted" data-score-view="weighted">Weighted</a>

						<?php if($active): ?>
							<span>(division scoring method, <?php echo e($division->captionWeighting->name); ?>)</span>
						<?php else: ?>
							<span>(<?php echo e($division->captionWeighting->name); ?>)</span>
						<?php endif; ?>

					</li>
					<li class="list-group-item">
						<a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
					</li>
				</ul>

				<?php echo $__env->make('scores.public.composite',['choirs' => $choirs, 'judges' => $judges, 'scoreboard' => $scoreboard], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public_results', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>