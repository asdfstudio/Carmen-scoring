<?php $judge_id = $judge ? $judge->id : null; ?>
<?php if(!$choirs->isEmpty()): ?>
<table class="table scoreboard last-col-right">
  <tr>
  	<th>Choir</th>
    <th>My Raw Score</th>

    <?php if($division->captionWeighting->slug == '60-40'): ?>
      <th>
        My Weighted Score
      </th>
    <?php endif; ?>

    <th>Actions</th>
  </tr>

  <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <tr>
  	<td>
      <?php if( $choir->school && $choir->school->name ): ?>
        <span class="subheading"><?php echo e($choir->school->name); ?></span>
      <?php endif; ?>
      <?php echo e($choir->name); ?>

    </td>

    <td>
			<?php $aggregateScore = $rawScores->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('score');?>
      <span class="score raw"><?php echo e($aggregateScore); ?></span>
    </td>

    <?php if($division->captionWeighting->slug == '60-40'): ?>
      <td>
        <?php $aggregateScore = $weightedScores->where('choir_id',$choir->id)->where('judge_id', $judge->id)->sum('weightedScore');?>
        <span class="score weighted"><?php echo e($aggregateScore); ?></span>
      </td>
    <?php endif; ?>

    <td>

      <?php if($round->is_scoring_active AND $judge_id == Auth::user()->person_id): ?>

        <?php
        $anchor_text = 'Enter My Scores';

        if($aggregateScore > 0)
        {
          $anchor_text = 'Update My Scores';
        }

        ?>

        <?php echo e(link_to_route('judge.competition.division.round.choir.show', $anchor_text, [$round->division->competition,$round->division,$round,$choir],
        ['class' => 'action'])); ?>

      <?php endif; ?>


      <?php if($round->is_scoring_active == false AND $judge_id == Auth::user()->person_id): ?>

        <?php echo e(link_to_route('judge.competition.division.round.choir.show', 'View My Scores', [$round->division->competition,$round->division,$round,$choir],
        ['class' => 'action'])); ?>


      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php endif; ?>
