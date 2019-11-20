<?php if(!$soloDivisions->isEmpty()): ?>
	<ul class="list-group">
  <?php $__currentLoopData = $soloDivisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soloDivision): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <li class="list-group-item"><?php echo e(link_to_route('organizer.competition.solo-division.show', $soloDivision->name, [$soloDivision->competition,$soloDivision])); ?>

		<?php echo $soloDivision->status_label('pull-right'); ?>

	</li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
<?php endif; ?>
