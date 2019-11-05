<?php if(session('access_code_alert')): ?>
  <p class="alert alert-danger"><?php echo e(session('access_code_alert')); ?></p>
<?php endif; ?>

<div class="alert alert-info">
  <h3>Particants - Access Full Results</h3>
  <p>There are two ways to access the full results:</p>
  <ol>
    <li>Enter the access code for this division that was provided by your competition.</li>
    <li>Enter the email address on file for the director of your choir.</li>
  </ol>

  <?php echo form($accessCodeForm); ?>

</div>
