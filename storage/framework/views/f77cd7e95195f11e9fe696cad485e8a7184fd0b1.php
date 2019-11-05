<?php if(session('access_code_alert')): ?>
  <p class="alert alert-danger"><?php echo e(session('access_code_alert')); ?></p>
<?php endif; ?>

<div class="alert alert-info">
  <h3>Particants - Access Full Results</h3>
  <p>Enter the access code for this competition.</p>

  <?php echo form($accessCodeForm); ?>

</div>
