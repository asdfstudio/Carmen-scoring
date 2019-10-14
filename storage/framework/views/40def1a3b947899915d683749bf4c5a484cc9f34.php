<div class="header-wrap organizer">
  <div class="header body-width">
    <ul class="navigation">
      <?php if(Auth::user()->isAdmin()): ?>
        <li class="title">
          <span class="intro">Acting as:</span>
          <span class="organization-name"><?php echo e(Auth::user()->organization->name); ?></span>
        </li>
      <?php else: ?>
        <li class="title">Organizer: <?php echo e(Auth::user()->display_name); ?></li>
      <?php endif; ?>


      <li>
        <?php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'competition' ? 'active' : false; ?>
        <a href="<?php echo e(route('organizer.competition.index')); ?>" class="<?php echo e($link_class); ?>">Competitions</a>
      </li>
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('showAll','App\User')): ?>
        <li>
          <?php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'user' ? 'active' : false; ?>
          <a href="<?php echo e(route('organizer.user.index')); ?>" class="<?php echo e($link_class); ?>">Users</a>
        </li>
      <?php endif; ?>
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('showAll','App\Penalty')): ?>
        <li>
          <?php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'penalty' ? 'active' : false; ?>
          <a href="<?php echo e(route('organizer.penalty.index')); ?>" class="<?php echo e($link_class); ?>">Penalties</a>
        </li>
      <?php endif; ?>
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('showAll','App\Award')): ?>
        <li>
          <?php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'award' ? 'active' : false; ?>
          <a href="<?php echo e(route('organizer.award.index')); ?>" class="<?php echo e($link_class); ?>">Awards</a>
        </li>
      <?php endif; ?>
      <li>
        <?php $link_class = Request::segment(1) == 'organizer' AND Request::segment(2) == 'organization' ? 'active' : false; ?>
        <a href="<?php echo e(route('organizer.organization.show')); ?>" class="<?php echo e($link_class); ?>">Organization</a>
      </li>

    </ul>

    <?php if(Auth::user()->isAdmin() == false): ?>
      <ul class="user-actions navigation">
        <li>
          <?php $link_class = Request::segment(1) == 'profile' ? 'active' : false; ?>
          <a href="<?php echo e(route('profile.edit')); ?>" class="<?php echo e($link_class); ?>">My Profile</a>
        </li>
        <li>
          <a href="<?php echo e(url('logout')); ?>">Logout</a>
        </li>
      </ul>
    <?php endif; ?>
  </div>
</div>
