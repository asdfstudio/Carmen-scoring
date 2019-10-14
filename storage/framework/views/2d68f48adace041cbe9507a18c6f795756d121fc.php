<?php if($choirs->isEmpty()): ?>
	<p>There are no choirs.</p>
<?php endif; ?>

<?php if(!$choirs->isEmpty()): ?>
<ul class="list-group">
  <?php foreach($choirs as $choir): ?>
	  <li class="choir list-group-item">
      <?php if($choir->school): ?>
        <span class="school"><?php echo e($choir->school->name); ?></span>
      <?php endif; ?>

			<span class="name"><?php echo e($choir->name); ?></span>

      <?php if($choir->school AND $choir->school->place AND $choir->school->place->city_state()): ?>
        <span class="location"><?php echo e($choir->school->place->city_state()); ?> </span>
      <?php endif; ?>

			<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('removeChoir', $division)): ?>
				<div class="actions-group">
					<?php echo form($deleteForm, ['url' => route('organizer.competition.division.choir.destroy',[$division->competition,$division,$choir])]); ?>

				</div>
			<?php endif; ?>

		</li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
