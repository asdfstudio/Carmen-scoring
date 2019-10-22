<?php if($choirs->isEmpty()): ?>
	<p>There are no choirs.</p>
<?php endif; ?>

<?php if(!$choirs->isEmpty()): ?>
<ul class="list-group">
  <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	  <li class="choir list-group-item">
      <?php if($choir->school): ?>
        <span class="school"><?php echo e($choir->school->name); ?></span>
      <?php endif; ?>

			<span class="name"><?php echo e($choir->name()); ?></span>

      <?php if($choir->school AND $choir->school->place AND $choir->school->place->city_state()): ?>
        <span class="location"><?php echo e($choir->school->place->city_state()); ?> </span>
      <?php endif; ?>


		</li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
