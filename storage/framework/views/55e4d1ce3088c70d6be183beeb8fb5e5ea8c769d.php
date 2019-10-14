<?php if($rawScoreFiles->isEmpty()): ?>
	<p>There are no criteria.</p>
<?php endif; ?>

<?php if(!$rawScoreFiles->isEmpty()): ?>
<ul class="list-group">
  <?php $__currentLoopData = $rawScoreFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rawScoreFile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li class="school list-group-item">

      <span class="name"><?php echo e($rawScoreFile['date']); ?></span>

      <ul class="actions-group">
        <li><?php echo e(link_to_route('admin.raw-score-log.show', 'View', [$rawScoreFile['date']], ['class' => 'action'])); ?></li>
      </ul>



    </li>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php endif; ?>
