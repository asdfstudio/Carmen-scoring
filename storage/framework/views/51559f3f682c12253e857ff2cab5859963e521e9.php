<?php $__env->startSection('breadcrumbs'); ?>
	<?php echo Breadcrumbs::render('results.division.show-public', $division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

		<h2>Standings</h2>

		<?php $__currentLoopData = $division->standings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $standing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="standing-container">

				<?php if($standing->caption_id == NULL): ?>
					<div class="content-subheader caption">
					<h3>Overall Standings</h3>
				<?php else: ?>
					<div class="content-subheader caption <?php echo e($standing->caption->background_css); ?>">
					<h2><?php echo e($standing->caption->name); ?> Standings</h2>
				<?php endif; ?>
				</div>

				<?php if($standing == false): ?>
				  <p>There are no final standings yet.</p>
				<?php endif; ?>

				<?php if($standing): ?>
				  <?php if($standing->is_consensus_scoring): ?>
				    <p class="alert alert-warning">Consensus scoring is used for this division.</p>
				  <?php endif; ?>

				  <?php echo $__env->make('standing.public_list', ['standing' => $standing, 'showSponsor' => false], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

				<?php endif; ?>
			</div>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public_results', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>