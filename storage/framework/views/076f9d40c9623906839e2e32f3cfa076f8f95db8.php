<?php if($criteria->isEmpty()): ?>
	<p>There are no criteria.</p>
<?php endif; ?>

<?php if(!$criteria->isEmpty()): ?>
<ul class="list-group">
  <?php foreach($criteria as $criterion): ?>
    <li class="school list-group-item">

      <div class="name">
				<?php echo e($criterion->name); ?>

				<div class="pull-right label">Max score: <?php echo e($criterion->max_score); ?></div>
			</div>

			<span class="label small count"><?php echo e($criterion->sheets->count()); ?> Sheets</span>
			<div class="description mv"><?php echo e($criterion->description); ?></div>

      <ul class="actions-group">
        <li><?php echo e(link_to_route('admin.criteria.edit', 'Edit Criterion', [$criterion], ['class' => 'action'])); ?></li>

				<?php foreach($criterion->sheets as $sheet): ?>
					<li><?php echo e(link_to_route('admin.sheet.manage', 'Manage ' . $sheet->name, [$sheet], ['class' => 'action'])); ?></li>
				<?php endforeach; ?>
      </ul>

    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
