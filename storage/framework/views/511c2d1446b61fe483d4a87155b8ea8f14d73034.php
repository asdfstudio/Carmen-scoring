<?php $__env->startSection('content-header'); ?>
  <h1>My Scores - Summary View</h1>

  <?php echo e(link_to_route('judge.round.scores.spreadsheet', 'Go to Spreadsheet View', [$competition, $division, $round], ['class' => 'action'])); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('division_navigation_bar'); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('round_navigation_bar'); ?>
  <?php if(isset($round) AND isset($division->rounds)): ?>
    <div class="round-navigation-bar body-width">
      <ul class="round-navigation">
        <?php $__currentLoopData = $division->rounds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php $active_class = $rd->id == $round->id ? 'active' : '';?>
          <li class="round-<?php echo e($rd->status_slug); ?>">
            <a href="<?php echo e(route('judge.round.scores.summary', [$competition, $division, $rd])); ?>" class="<?php echo e($active_class); ?>">
              <?php echo e($rd->name); ?>


              <?php echo $rd->status_label('round-navigation-link-status'); ?>

            </a>
          </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>

  <?php if($round->status_slug == 'completed'): ?>
    <ul class="actions-group mv-2">
      <li><?php echo e(link_to_route('judge.round.scores', "View All Judges' Scores", [$round->division->competition, $round->division, $round], ['class' => 'action'])); ?></li>
    </ul>
  <?php endif; ?>

  <?php if($round->targets->count() > 0): ?>
    <div class="alert alert-info">
      <h4>This round feeds into <strong><?php echo e($round->targets->first()->division->name); ?>, <?php echo e($round->targets->first()->name); ?></strong>  along with <?php echo e($round->targets->first()->sources->count()); ?> other round(s).</h4>
      <p><?php echo e(link_to_route('judge.round.scores.sources', 'View a combined spreadsheet', [$round->division->competition_id, $round->targets->first()->division, $round->targets->first()], ['class' => 'btn btn-primary'])); ?>&nbsp; showing your scores for all of these rounds together.</p>
    </div>
  <?php endif; ?>

  <?php echo $__env->make('scores.choirs_judge_aggregate',[
    'choirs' => $round->choirs,
    'division' => $round->division,
    'judge' => $round->division->judges->first()
  ], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>