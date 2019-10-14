<?php if($users->isEmpty()): ?>
	<p>There are no users.</p>
<?php endif; ?>

<?php if(!$users->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
		<th>Name</th>
		<th>Username</th>
    <th>Email</th>
		<th>Role Type</th>

	  <th>Edit</th>
		<th>Delete</th>
  </tr>

  <?php foreach($users as $user): ?>
  <tr>
		<td>
			<?php if($user->person): ?>
				<?php echo e($user->person->full_name); ?>

			<?php endif; ?>
		</td>
		<td><?php echo e($user->username); ?></td>
  	<td><?php echo e($user->email); ?></td>
		<td><?php echo e($user->organization_role); ?></td>


		<td>
			<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('update' , $user)): ?>
				<?php echo e(link_to_route('organizer.user.edit', 'Edit', [$user])); ?>

			<?php endif; ?>
		</td>


		<td>
			<?php if (app('Illuminate\Contracts\Auth\Access\Gate')->check('destroy' , $user)): ?>
				<?php echo form($deleteUserForm,['url' => route('organizer.user.destroy',[$user])]); ?>

			<?php endif; ?>
		</td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
