
<?php if(!Auth::guest()): ?>
  <?php if(Auth::user()->isAdmin()): ?>
    <?php echo $__env->make('navigation/admin/header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endif; ?>

  <?php if(Auth::user()->isOrganizer() AND Request::segment(1) != 'admin'): ?>
    <?php echo $__env->make('navigation/organizer/header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endif; ?>

  <?php if(Auth::user()->isJudge()): ?>
    <?php echo $__env->make('navigation/judge/header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endif; ?>
<?php endif; ?>
