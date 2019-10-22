<?php if($judges->isEmpty()): ?>
	<p>There are no judges.</p>
<?php endif; ?>

<?php if(!$judges->isEmpty()): ?>
<ul class="list-group">
  <?php $__currentLoopData = $judges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	  <li class="judge list-group-item">
			<span class="name"><?php echo e($judge->full_name); ?></span>

      <ul class="captions-group">
      <?php $__currentLoopData = $captions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

          <?php if(in_array($caption->id, $judge->captions->pluck('id')->toArray() )): ?>
            <li class="<?php echo e($caption->slug); ?> caption label <?php echo e($caption->background_css); ?>"><?php echo e($caption->name); ?></li>
          <?php endif; ?>


      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>

			<!--<ul class="actions-group">
				<li>
					<?php echo e(link_to_route('organizer.competition.division.judge.edit', 'Edit', [$division->competition,$division,$judge], ['class' => 'action'])); ?>

				</li>
			</ul>-->

		</li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
