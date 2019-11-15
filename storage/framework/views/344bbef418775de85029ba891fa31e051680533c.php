<?php if($rounds->isEmpty()): ?>
	<p>There are no rounds.</p>
<?php endif; ?>

<?php if(!$rounds->isEmpty()): ?>
<ul class="list-group">
  <?php $__currentLoopData = $rounds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $round): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	  <li class="round list-group-item">
			<span class="name"><?php echo e(link_to_route('organizer.competition.division.round.show', $round->name, [$division->competition,$division,$round])); ?></span>

			<span class="label status <?php echo e($round->status_slug()); ?>"><?php echo e($round->status()); ?></span>

			<div>Order: <?php echo e($round->sequence); ?></div>

			<div>Number of participating choirs: <?php echo e($round->max_choirs_text); ?></div>

			<?php if(!$round->choirs->isEmpty()): ?>
				<h4>Choirs</h4>
				<ul>
					<?php $__currentLoopData = $round->choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($choir->full_name); ?></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			<?php endif; ?>

			<?php if(!$round->sources->isEmpty()): ?>
				<h4>Source(s) - <?php echo e(link_to_route('organizer.competition.division.round.show_sources', 'View combined scores', [$division->competition_id, $division, $round])); ?></h4>
				<ul>
					<?php $__currentLoopData = $round->sources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e(link_to_route('organizer.competition.division.round.show', $source->full_name, [$division->competition_id, $division, $source->id])); ?></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			<?php endif; ?>

			<?php if(!$round->targets): ?>
				<h4>Target</h4>
				<ul>
					<?php $__currentLoopData = $round->targets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $target): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li><?php echo e($target->full_name); ?></li>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</ul>
			<?php endif; ?>

			<ul class="actions-group">
				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $round)): ?>
					<li>
						<?php echo e(link_to_route('organizer.competition.division.round.edit', 'Edit', [$division->competition,$division,$round], ['class' => 'action'])); ?>

					</li>
				<?php endif; ?>

				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setPerformanceOrder', $round)): ?>
					<li>
						<?php echo e(link_to_route('organizer.competition.division.round.choir.performance_order', 'Set Choir Performance Order', [$division->competition,$division,$round], ['class' => 'action'])); ?>

					</li>
				<?php endif; ?>

				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('activateScoring', $round)): ?>
					<li>
						<?php echo form($activateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id]), 'class' => '']); ?>

					</li>
				<?php endif; ?>

				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deactivateScoring', $round)): ?>
					<li>
						<?php echo form($deactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id]), 'class' => '']); ?>

					</li>
				<?php endif; ?>

				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('completeScoring', $round)): ?>

					<?php

					if ($round->isMissingScores()) {
	          $btnAttr = ['class' => 'action disabled', 'disabled' => 'disabled'];
	        } else {
	          $btnAttr = ['class' => 'action'];
	        }

					$completeScoringForm->modify('submit', 'submit', [
						'attr' => $btnAttr
					]);
					?>
					<li>
						<?php echo form($completeScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id]), 'class' => '']); ?>

					</li>
				<?php endif; ?>

				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('reactivateScoring', $round)): ?>
					<li>
						<?php echo form($reactivateScoringForm, ['url' => route('organizer.competition.division.round.scoring',[$division->competition->id,$division->id,$round->id]), 'class' => '']); ?>

					</li>
				<?php endif; ?>

			</ul>

		</li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
