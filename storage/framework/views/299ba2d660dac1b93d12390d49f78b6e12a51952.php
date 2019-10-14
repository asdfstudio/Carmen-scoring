<?php $__env->startSection('body-content'); ?>
  <?php echo $__env->make('navigation/header', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php if(empty($competition) AND isset($division->competition)): ?>
    <?php $competition = $division->competition;?>
  <?php endif; ?>

  <?php if(isset($competition)): ?>
  <div class="competition-bar-wrap">
    <div class="competition-bar body-width">
      <div class="carmen-logo-wrap">
        <img src="/images/Carmen-Logo-185x60.png"  />
      </div>
      <div class="heading">
        <?php echo e(link_to_route(Request::segment(1) . '.competition.show', $competition->name, [$competition])); ?>

        <?php if($competition->place): ?>
          <span class="subheading"><?php echo e($competition->place->city); ?>, <?php echo e($competition->place->state); ?></span>
        <?php endif; ?>
      </div>

      <div class="status-container">
        <?php echo $competition->status_label('pull-right'); ?>

      </div>

    </div>
  </div>
  <?php endif; ?>

  <?php if(isset($division)): ?>
  <div class="division-bar body-width">
    <div class="heading">
      <?php echo e(link_to_route( Request::segment(1) . '.competition.division.show', $division->name, [$division->competition, $division])); ?>

      <?php echo $division->status_label(); ?>

    </div>
    <div class="division-actions">
      <ul class="actions-group">
        <li>
          <?php echo e(link_to_route( Request::segment(1) . '.competition.show', 'All Divisions', [$division->competition], ['class' => 'action'])); ?>

        </li>
      </ul>
    </div>
  </div>
  <?php endif; ?>

  <?php $__env->startSection('division_navigation_bar'); ?>
    <?php if(isset($division)): ?>
      <div class="division-navigation-bar body-width">
        <ul class="division-navigation">
          <li>
            <?php $link_class = in_array(Request::segment(6),['overview']) ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.show', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Overview</a>
          </li>
          <li>
            <?php $link_class = in_array(Request::segment(6),['settings','edit']) ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.settings', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Settings</a>
          </li>
          <li>
            <?php $link_class = Request::segment(6) == 'choir' ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.choir.index', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Choirs
              <span class="count"><?php echo e($division->choirs->count()); ?></span>
            </a>
          </li>
          <li>
            <?php $link_class = Request::segment(6) == 'judge' ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.judge.index', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Judges
              <span class="count"><?php echo e($division->judges->unique('id')->count()); ?></span></a>
          </li>
          <li>
            <?php $link_class = Request::segment(6) == 'round' ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.round.index', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Rounds
            <span class="count"><?php echo e($division->rounds->count()); ?></span>
            </a>
          </li>
          <li>
            <?php $link_class = Request::segment(6) == 'penalty' ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.penalty.index', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Penalties
            <span class="count"><?php echo e($division->penalties->count()); ?></span>
            </a>
          </li>
          <li>
            <?php $link_class = Request::segment(6) == 'award' ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.award.index', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Awards
              <span class="count"><?php echo e($division->awards->count()); ?></span>
            </a>
          </li>
          <li>
            <?php $link_class = Request::segment(6) == 'standing' ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.standing.show', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Final Standings</a>
          </li>
          <li>
            <?php $link_class = Request::segment(6) == 'ceremony' ? 'active' : false; ?>
            <a href="<?php echo e(route('organizer.competition.division.ceremony.show', [$competition, $division])); ?>" class="<?php echo e($link_class); ?>">Award Ceremony</a>
          </li>

        </ul>
      </div>
    <?php endif; ?>
  <?php echo $__env->yieldSection(); ?>

  <?php $__env->startSection('round_navigation_bar'); ?>

  <?php echo $__env->yieldSection(); ?>

  <div class="collapse content body-width">

    <?php if (! empty(trim($__env->yieldContent('content-header')))): ?>
      <div class="content-header">
        <?php echo $__env->yieldContent('content-header'); ?>
      </div>
    <?php endif; ?>

    <?php $__env->startSection('alert'); ?>
      <?php echo $__env->make('alert/all', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->yieldSection(); ?>

    <?php echo $__env->yieldContent('content'); ?>
  </div>

  <?php if (! empty(trim($__env->yieldContent('breadcrumbs')))): ?>
    <div class="breadcrumbs-footer body-width">
      <?php echo $__env->yieldContent('breadcrumbs'); ?>
    </div>
  <?php endif; ?>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>