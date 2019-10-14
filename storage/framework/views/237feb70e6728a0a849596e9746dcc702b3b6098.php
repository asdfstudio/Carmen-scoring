<?php $__env->startSection('content'); ?>
		
    <h1>Create a person</h1>
    
		<?php echo form($form); ?>

    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>