<?php if(!$divisions->isEmpty()): ?>
	<ul class="list-group">
  <?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $division): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <li class="list-group-item"><?php echo e(link_to_route('organizer.competition.division.show', $division->name, [$division->competition,$division])); ?>

		<?php echo $division->status_label('pull-right'); ?>

	</li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
<?php endif; ?>
