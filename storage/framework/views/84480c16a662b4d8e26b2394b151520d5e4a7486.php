<?php if(Session::has('success')): ?>
  <div class="alert alert-success">
      <?php echo e(Session::get('success')); ?>

  </div>
<?php endif; ?>
