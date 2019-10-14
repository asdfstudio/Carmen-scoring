
<ul class="list-group">
	<?php if($division->captionWeighting): ?>
		<li class="list-group-item">Caption Weighting: <?php echo e($division->captionWeighting->name); ?></li>
	<?php endif; ?>

	<?php if($division->scoringMethod): ?>
  	<li class="list-group-item">Scoring Method: <?php echo e($division->scoringMethod->name); ?></li>
	<?php endif; ?>

	<?php if($division->sheet): ?>
  	<li class="list-group-item">Scoring Sheet: <?php echo e($division->sheet->name); ?></li>
	<?php endif; ?>

	<!--<li class="list-group-item">Overall Awards: <?php echo e($division->overall_award_count); ?></li>

	<li class="list-group-item">Music Awards: <?php echo e($division->music_award_count); ?></li>
	<li class="list-group-item">Show Awards: <?php echo e($division->show_award_count); ?></li>
	<li class="list-group-item">Combo Awards: <?php echo e($division->combo_award_count); ?></li>-->
</ul>
