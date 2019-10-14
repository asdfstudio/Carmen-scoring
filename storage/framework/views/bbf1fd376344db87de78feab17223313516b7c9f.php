<?php $__env->startSection('content-header'); ?>
  <h1><?php echo e($judge->full_name); ?></h1>

  <ul class="actions-group">
    <li>
      <?php echo e(link_to_route('organizer.competition.division.judge.index','Back to judges', [$division->competition->id, $division->id], ['class' => 'action'])); ?>

    </li>
  </ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
		<h2>Choose captions for <?php echo e($judge->first_name); ?> to score</h2>
		<?php echo form($form); ?>


    <h2>Remove <?php echo e($judge->first_name); ?> from this division</h2>

    <?php echo form($deleteForm); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>