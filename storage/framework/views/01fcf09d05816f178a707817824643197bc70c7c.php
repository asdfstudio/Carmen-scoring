<?php if($rounds->isEmpty()): ?>
	<p>There are no rounds.</p>
<?php endif; ?>

<?php if(!$rounds->isEmpty()): ?>
<ul class="list-group">
  <?php $__currentLoopData = $rounds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $round): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	  <li class="round list-group-item">
			<span class="name"><?php echo e(link_to_route('judge.round.scores.summary', $round->name, [$division->competition,$division,$round])); ?></span>
			<span class="label status <?php echo e($round->status_slug()); ?>"><?php echo e($round->status()); ?></span>

		</li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
