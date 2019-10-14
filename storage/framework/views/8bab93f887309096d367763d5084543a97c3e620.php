<?php $__env->startSection('content'); ?>
  <h1 class="division-heading">Sorry, the page you are looking for isn't available.</h1>

  <!--<h2>What next?</h2>
  <p>We'll work to get the error fixed ASAP.</p>-->

  <h3>Are you a judge?</h3>
  <p>Please let your competition organizer know that you received an error.</p>

  <h3>Are you a competition organizer?</h3>
  <p>Please contact Carmen to let us know that you received an error.</p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>