<?php $__env->startSection('content'); ?>

		<?php echo Breadcrumbs::render('admin.school.show', $school); ?>


		<h1>School Details</h1>

		<?php echo $__env->make('school.partial.single', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <?php if($school->place): ?>
			<?php echo $__env->make('place.show',['place' => $school->place], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>

    <?php if($school->choirs): ?>
			<?php echo $__env->make('choir.table',['choirs' => $school->choirs], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>