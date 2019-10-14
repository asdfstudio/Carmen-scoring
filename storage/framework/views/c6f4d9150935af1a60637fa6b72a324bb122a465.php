<?php if($judges->isEmpty()): ?>
	<p>There are no judges.</p>
<?php endif; ?>

<?php if(!$judges->isEmpty()): ?>
<ul class="list-group">
  <?php foreach($judges as $judge): ?>
	  <li class="judge list-group-item">
			<span class="name"><?php echo e($judge->full_name); ?></span>

      <ul class="captions-group">
      <?php foreach($captions as $caption): ?>

          <?php if(in_array($caption->id, $judge->captions->pluck('id')->toArray() )): ?>
            <li class="<?php echo e($caption->slug); ?> caption label <?php echo e($caption->background_css); ?>"><?php echo e($caption->name); ?></li>
          <?php endif; ?>


      <?php endforeach; ?>
      </ul>


			<ul class="actions-group">
				<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('updateJudge', $division)): ?>
					<li>
						<?php echo e(link_to_route('organizer.competition.division.judge.edit', 'Edit Captions', [$division->competition,$division,$judge], ['class' => 'action'])); ?>

					</li>
				<?php endif; ?>
				<?php if($judge->user): ?>
					<li>
						<?php echo e(link_to_route('user.password.edit', 'Change Password', [$judge->user->id], ['class' => 'action'])); ?>

					</li>
				<?php endif; ?>
			</ul>

		</li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
