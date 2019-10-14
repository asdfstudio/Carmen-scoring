<div class="header-wrap judge">
  <div class="header body-width">
    <ul class="navigation">
      <!--<li class="title">Judge</li>-->

      <li class="title"><?php echo e(Auth::user()->display_name); ?></li>

      <li>
        <?php $link_class = Request::segment(1) == 'judge' AND Request::segment(2) == 'competitions' ? 'active' : false; ?>
        <a href="<?php echo e(route('judge.competition.index')); ?>" class="<?php echo e($link_class); ?>">My Competitions</a>
      </li>

    </ul>

    <?php if(Auth::user()->isOrganizer() == false): ?>
      <ul class="user-actions navigation">
        <li>
          <a href="<?php echo e(route('profile.edit')); ?>">My Profile</a>
        </li>
        <li>
          <a href="<?php echo e(url('logout')); ?>">Logout</a>
        </li>
      </ul>
    <?php endif; ?>
  </div>
</div>
