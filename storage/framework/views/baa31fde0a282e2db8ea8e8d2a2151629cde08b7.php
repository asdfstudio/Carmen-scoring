<?php $__env->startSection('breadcrumbs'); ?>
  <?php echo Breadcrumbs::render('organizer.competition.division.edit',$competition,$division); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
  <h1>Edit a division</h1>

  <ul class="actions-group">
		<li><?php echo e(link_to_route('organizer.competition.division.settings','Back to Settings',[$competition, $division],['class' => 'action'])); ?></li>
	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

		<?php echo form_start($form); ?>

      
      <?php echo form_until($form, 'rating_system_heading'); ?>

      
      <div class="rating-system collection-container form-group" data-prototype="<?php echo e(form_row($form->rating_system->prototype())); ?>">
        <?php echo form_row($form->rating_system); ?>

      </div>
      
		<?php echo form_end($form); ?>


    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('destroy', $division)): ?>
      <hr>

      <h3>Delete this division?</h3>
      <p class="alert alert-danger">This is a permanent, irrecoverable action. Proceed with caution.</p>
      <?php echo form($deleteForm); ?>

    <?php endif; ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>