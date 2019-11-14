<?php if($penalties->isEmpty()): ?>
	<p>There are no penalties.</p>
<?php endif; ?>

<?php if(!$penalties->isEmpty()): ?>
<ul class="list-group">
  <?php $__currentLoopData = $penalties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $penalty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	  <li class="penalty list-group-item">
			<div class="group pull-left">

				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update',$penalty)): ?>
					<a class="name" href="<?php echo e(route('organizer.penalty.edit',[$penalty])); ?>"><?php echo e($penalty->name); ?></a>
				<?php endif; ?>

				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('update',$penalty)): ?>
					<span class="name"><?php echo e($penalty->name); ?></span>
				<?php endif; ?>

				<span class="description"><?php echo e($penalty->description); ?></span>



			</div>

			<span class="details pull-right">
				<span class="amount">-<?php echo e($penalty->amount); ?></span>
				points
				<span class="apply_per_judge"><?php echo e($penalty->apply_per_judge_text()); ?></span>
			</span>

			<ul class="actions-group">
				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update',$penalty)): ?>
					<li>
						<a class="action secondary" href="<?php echo e(route('organizer.penalty.edit',[$penalty])); ?>">Edit</a>
					</li>
				<?php endif; ?>
			</ul>


		</li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
