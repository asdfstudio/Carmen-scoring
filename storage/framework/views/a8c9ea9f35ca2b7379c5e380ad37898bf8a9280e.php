<?php $__env->startSection('content-header'); ?>
	<h1>Awards</h1>

	<ul class="actions-group">
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create' , ['App\Award'])): ?>
		  <li><?php echo e(link_to_route('organizer.award.create','Add an award',NULL,['class' => 'action'])); ?></li>
		<?php endif; ?>

	</ul>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<p class="content-intro">
		This pages lists all of your award options. There are standard Carmen Scoring System awards and custom awards that you can create for your organization. For each division of your competition, you can choose which of these awards you'd like to give out.
	</p>

	<h2>Custom Awards</h2>
	<p class="content-intro">
		Custom awards are created and used by your organization. They can be used in as many competitions and divisions as you'd like.
	</p>
  <?php echo $__env->make('award.organizer.list', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

	<h2>Standard Awards</h2>
	<p class="content-intro">
		Standard awards are the default awards that were created by Carmen Scoring. If an award doesn't exist here, you can create a custom award for your organization.
	</p>
  <?php echo $__env->make('award.organizer.list', ['awards' => $standard_awards], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>