<?php if($sheets->isEmpty()): ?>
	<p>There are no criteria.</p>
<?php endif; ?>

<?php if(!$sheets->isEmpty()): ?>
<ul class="list-group">
  <?php foreach($sheets as $sheet): ?>
    <li class="school list-group-item">

      <span class="name"><?php echo e($sheet->name); ?></span>
			<ul class="list-group">
				<li class="list-group-item">Criteria: <?php echo e($sheet->criteria()->count()); ?></li>
				<li class="list-group-item">Total Points Available: <?php echo e($sheet->max_score); ?> (<?php echo e($sheet->weighted_max_score); ?> if using Weighted Scoring)</li>
			</ul>

      <ul class="actions-group">
        <li><?php echo e(link_to_route('admin.sheet.show', 'View', [$sheet], ['class' => 'action'])); ?></li>
				<li><?php echo e(link_to_route('admin.sheet.edit', 'Edit', [$sheet], ['class' => 'action'])); ?></li>
				<li><?php echo e(link_to_route('admin.sheet.manage', 'Manage Criteria', [$sheet], ['class' => 'action'])); ?></li>
				<li><?php echo e(link_to_route('admin.sheet.manage-order', 'Manage Criteria Display Order', [$sheet], ['class' => 'action'])); ?></li>
				<li><?php echo e(link_to_route('admin.sheet.manage-caption-order', 'Manage Caption Display Order', [$sheet], ['class' => 'action'])); ?></li>
      </ul>



    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
