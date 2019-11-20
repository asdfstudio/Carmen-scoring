<?php if($awards->isEmpty()): ?>
	<p>There are no awards.</p>
<?php endif; ?>

<?php if(!$awards->isEmpty()): ?>
<ul class="list-group">
  <?php $__currentLoopData = $awards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $award): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	  <li class="award list-group-item">
			<span class="name"><?php echo e($award->name); ?></span>

			<?php if($award->description): ?>
				<span class="description"><?php echo e($award->description); ?></span>
			<?php endif; ?>

			<span class="owner"><?php echo e($award->owner()); ?></span>

			<?php if($award->pivot): ?>
				<?php if($award->pivot->recipient ?? $award->choir): ?>
					<span class="recipient">
						<span class="heading">Recipient:</span>
						<?php if($award->pivot->recipient): ?>
							<span class="name"><?php echo e($award->pivot->recipient); ?></span>
						<?php endif; ?>
						<?php if($award->choir): ?>
							<span class="choir"><?php echo e($award->choir->full_name); ?></span>
						<?php endif; ?>
					</span>
				<?php endif; ?>
			<?php endif; ?>

			<?php if($award->pivot->sponsor): ?>
				<span class="sponsor">
					<span class="heading">Sponsored by:</span>
					<span class="name"><?php echo e($award->pivot->sponsor); ?></span>
				</span>
			<?php endif; ?>

		</li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
