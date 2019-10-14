<?php $__env->startSection('content-header'); ?>
  <h1>Delete Blanks</h1>
  <a href="<?php echo e(route('admin.dedup')); ?>" class="action">Back</a>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
  
  <?php if($blank_count): ?>
    <p>
      <?php echo e($blank_count); ?> blank record(s) have been deleted from the <code>people</code> table in
      the database. No further action is necessary.
    </p>
  <?php else: ?>
    <p>There are no blank records in the <code>people</code> table in the database.</p>
  <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>