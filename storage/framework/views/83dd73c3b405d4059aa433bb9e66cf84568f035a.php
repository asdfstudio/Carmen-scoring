<?php $__env->startSection('content-header'); ?>
  <h1>All Scores - Summmary View</h1>

  <?php echo e(link_to_route('judge.round.scores.summary', 'Go to My Scores', [$competition, $division, $round], ['class' => 'action'])); ?>

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
  
  
  <?php if($division->scoring_method_id === 1 && $division->caption_weighting_id === 2): ?>
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  <?php endif; ?>
  
  
  <?php if($division->scoring_method_id === 1 && $division->caption_weighting_id === 1): ?>
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#weighted" data-score-view="weighted">Weighted</a>
        <span>(division scoring method, <?php echo e($division->captionWeighting->name); ?>)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  <?php endif; ?>
  
  
  <?php if($division->scoring_method_id > 1 && $division->caption_weighting_id === 2): ?>
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="rank">Rankings</a>
        <span>(division scoring method)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  <?php endif; ?>

  
  <?php if($division->scoring_method_id > 1 && $division->caption_weighting_id === 1): ?>
    <ul class="list-group horizontal">
      <li class="list-group-item">
        <a class="score-view-toggle active division-scoring-method" href="#rankings" data-score-view="rank">Rankings</a>
        <span>(division scoring method)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#weighted" data-score-view="weighted">Weighted</a>
        <span>(<?php echo e($division->captionWeighting->name); ?>)</span>
      </li>
      <li class="list-group-item">
        <a class="score-view-toggle" href="#raw" data-score-view="raw">Raw</a>
      </li>
    </ul>
  <?php endif; ?>
  
  
  <?php if($division->scoring_method_id === 3 || $division->scoring_method_id === 4): ?>
  	<?php echo $__env->make('scores.organizer.ranked_condorcet',['choirs' => $choirs, 'judges' => $division->judges], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php endif; ?>

  <?php echo $__env->make('scores.judge.composite',['choirs' => $division->choirs], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>