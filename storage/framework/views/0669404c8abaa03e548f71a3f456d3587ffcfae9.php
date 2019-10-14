<div class="header-wrap admin">
  <div class="header body-width">
    <ul class="navigation">
      <li class="title">Admin</li>
      <li>
        <?php $link_class = Request::segment(2) == 'organization' ? 'active' : false; ?>
        <a href="<?php echo e(route('admin.organization.index')); ?>" class="<?php echo e($link_class); ?>">Organizations</a>
      </li>
      <li>
        <?php $link_class = Request::segment(2) == 'user' ? 'active' : false; ?>
        <a href="<?php echo e(route('admin.user.index')); ?>" class="<?php echo e($link_class); ?>">Users</a>
      </li>
      <li>
        <?php $link_class = Request::segment(2) == 'choir' ? 'active' : false; ?>
        <a href="<?php echo e(route('admin.choir.index')); ?>" class="<?php echo e($link_class); ?>">Choirs</a>
      </li>
      <li>
        <?php $link_class = Request::segment(2) == 'school' ? 'active' : false; ?>
        <a href="<?php echo e(route('admin.school.index')); ?>" class="<?php echo e($link_class); ?>">Schools</a>
      </li>

      <li>
        <?php $link_class = Request::segment(2) == 'sheet' ? 'active' : false; ?>
        <a href="<?php echo e(route('admin.sheet.index')); ?>" class="<?php echo e($link_class); ?>">Sheets</a>
      </li>

      <li>
        <?php $link_class = Request::segment(2) == 'criteria' ? 'active' : false; ?>
        <a href="<?php echo e(route('admin.criteria.index')); ?>" class="<?php echo e($link_class); ?>">Criteria</a>
      </li>

      <li>
        <?php $link_class = Request::segment(2) == 'caption' ? 'active' : false; ?>
        <a href="<?php echo e(route('admin.caption.index')); ?>" class="<?php echo e($link_class); ?>">Captions</a>
      </li>

      <li>
        <?php $link_class = Request::segment(2) == 'raw-score-log' ? 'active' : false; ?>
        <a href="<?php echo e(route('admin.raw-score-log.index')); ?>" class="<?php echo e($link_class); ?>">Logs</a>
      </li>

      <?php if(env('IS_WORKSHOP_ENABLED')): ?>
        <li>
          <?php $link_class = Request::segment(2) == 'workshop' ? 'active' : false; ?>
          <a href="<?php echo e(route('workshop.index')); ?>" class="<?php echo e($link_class); ?>">Workshop</a>
        </li>
      <?php endif; ?>


    </ul>

    <ul class="user-actions navigation">
      <li>
        <?php $link_class = Request::segment(1) == 'profile' ? 'active' : false; ?>
        <a href="<?php echo e(route('profile.edit')); ?>" class="<?php echo e($link_class); ?>">My Profile</a>
      </li>
      <li>
        <a href="<?php echo e(url('logout')); ?>">Logout</a>
      </li>
    </ul>
  </div>
</div>
