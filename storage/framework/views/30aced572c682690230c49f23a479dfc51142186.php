<?php $__env->startSection('content-header'); ?>
  <h1>Add a judge to this division</h1>
  <ul class="actions-group">
    <li>
      <?php echo e(link_to_route('organizer.competition.division.judge.index','Back to judges', [$division->competition->id, $division->id], ['class' => 'action'])); ?>

    </li>
  </ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
		<?php echo form($form); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('body-footer'); ?>
	<script>
    jQuery(document).ready(function($){
      var judgeSelectize = $('.judge_id').selectize({
        allowEmptyOption: true,
        placeholder: 'Select a judge...'
      });
    });
  </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>