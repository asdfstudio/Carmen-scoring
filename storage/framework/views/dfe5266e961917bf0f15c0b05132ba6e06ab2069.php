<?php $__env->startSection('breadcrumbs'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content-header'); ?>
	<h1>User &amp; Person Records</h1>

	<ul class="actions-group">
		<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create' , 'App\User')): ?>
		  <li><?php echo e(link_to_route('admin.user.create', 'Add a user', [], ['class' => 'action'])); ?></li>
		  <li><?php echo e(link_to_route('admin.person.create', 'Add a person', [], ['class' => 'action'])); ?></li>
		<?php endif; ?>
	</ul>

  <div style="clear: both;">

  <div id="user-person-filter-group">
    <a href="#" class="btn btn-primary user-person-filter active" data-filter="">View All</a>
    <a href="#" class="btn btn-primary user-person-filter" data-filter="users-only">Users Only</a>
    <a href="#" class="btn btn-primary user-person-filter" data-filter="non-users-only">Non-Users Only</a>
  </div>
  <form id="user-person-search">
    <img src='data:image/svg+xml;utf8,<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" class="svg-inline--fa fa-search fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>'>
    <input type="text" placeholder="Search name, username, or email">
  </form>
  
  <hr>
  
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <div id="user-person-list">

    <?php if($people->isEmpty()): ?>
      <p>There are no records to show.</p>
    <?php endif; ?>

    <?php if(!$people->isEmpty()): ?>
      <?php $__currentLoopData = $people; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $person): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div id="person-<?php echo e($person->id); ?>" class="person <?php echo e(isset($person->user) ? 'user' : 'non-user'); ?> <?php echo e($i % 2 !== 0 ? 'even' : ''); ?>" data-fullname="<?php echo e($person->full_name); ?>" data-firstname="<?php echo e($person->first_name); ?>" data-lastname="<?php echo e($person->last_name); ?>" data-username="<?php echo e(isset($person->user) ? $person->user->username : ''); ?>" data-email="<?php echo e($person->email); ?>">
          <?php if($person->user): ?>
            <div class="user-flag">
              User
            </div>
          <?php else: ?>
            <div class="user-flag">
              Non-User
            </div>
          <?php endif; ?>
          <div class="name"><span class="name-part first-name"><?php echo e($person->first_name); ?></span> <span class="name-part last-name"><?php echo e($person->last_name); ?></span></div>
          <div class="user-blocks">
            <div class="user-details">
              <?php if($person->user): ?>
              <div class="username detail">
                <span class="detail-label">Username:</span>
                <span class="detail-value"><?php echo e($person->user->username); ?></span>
              </div>
              <?php endif; ?>
              <div class="email detail">
                <span class="detail-label">Email:</span>
                <span class="detail-value"><?php echo e($person->email); ?></span>
              </div>
              <?php if($person->emails_additional): ?>
                <div class="emails-additional detail">
                  <span class="detail-label">Additional Emails:</span>
                  <span class="detail-value"><?php echo e($person->emails_additional); ?></span>
                </div>
              <?php endif; ?>
              <?php if($person->tel): ?>
                <div class="emails-additional detail">
                  <span class="detail-label">Phone:</span>
                  <span class="detail-value"><?php echo e($person->tel); ?></span>
                </div>
              <?php endif; ?>
              <?php if(!empty($type_names = $person->typeNames()) || ($person->user && $person->user->is_admin)): ?>
                <div class="roles detail">
                  <span class="detail-label">Roles:</span>
                  <span class="detail-value">
                    <?php if($person->user && $person->user->is_admin): ?>
                      <span class="role user-role">Carmen Admin</span>
                    <?php endif; ?>
                    <?php $__currentLoopData = $type_names; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <span class="role person-role"><?php echo e($type_name); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </span>
                </div>
              <?php endif; ?>
              <?php if(!empty($choirs = $person->choirs())): ?>
                <div class="choirs detail">
                  <span class="detail-label">Choirs:</span>
                  <span class="detail-value">
                    <?php $__currentLoopData = $choirs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j => $choir): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <a href="<?php echo e(route('admin.choir.show', [$choir])); ?>"><?php echo e($choir->name); ?></a><?php echo e($j < count($choirs)-1 ? ', ' : ''); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </span>
                </div>
              <?php endif; ?>
              <?php if(!empty($schools = $person->schools())): ?>
                <div class="schools detail">
                  <span class="detail-label">Schools:</span>
                  <span class="detail-value">
                    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j => $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <a href="<?php echo e(route('admin.school.edit', [$school])); ?>"><?php echo e($school->name); ?></a><?php echo e($j < count($schools)-1 ? ', ' : ''); ?>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </span>
                </div>
              <?php endif; ?>
              <?php if($person->user && $person->user->organization_id): ?>
                <div class="org-name detail">
                  <span class="detail-label">Organization:</span>
                  <span class="detail-value"><a href="<?php echo e(route('admin.organization.show', [$person->user->organization])); ?>"><?php echo e($person->user->organization->name); ?></a></span>
                </div>
                <?php if($person->user->organization_role): ?>
                  <div class="org-role detail">
                    <span class="detail-label">Organizational Role:</span>
                    <span class="detail-value"><?php echo e($person->user->organization_role === 'admin' ? 'Administrator' : ''); ?><?php echo e($person->user->organization_role === 'standard' ? 'Standard User' : ''); ?></span>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
            <div class="user-actions">
              <div class="user-actions-title">Actions:</div>
              <?php if($person->user): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $person->user)): ?>
                  <a href="<?php echo e(route('admin.user.edit', [$person->user])); ?>" class="btn action">Edit User</a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('destroy', $person->user)): ?>
                  <?php if(!$person->user->isSuperAdmin()): ?>
                    <?php echo form($deleteUserForm,['url' => route('admin.user.destroy',[$person->user])]); ?>

                  <?php endif; ?>
                <?php endif; ?>
              <?php else: ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $person)): ?>
                  <a href="<?php echo e(route('admin.person.edit', [$person])); ?>" class="btn action">Edit Person</a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('destroy', $person)): ?>
                  <?php echo form($deletePersonForm,['url' => route('admin.person.destroy',[$person])]); ?>

                <?php endif; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('body-footer'); ?>
  <script>
    $(document).ready(function(){
      
      $('.user-person-filter').click(function(e){
        e.preventDefault();
        $('.user-person-filter').removeClass('active');
        $(this).addClass('active');
        var filter = $(this).data('filter');
        $('#user-person-list').removeClass('users-only non-users-only').addClass(filter);
        $('#user-person-list .person').removeClass('even').filter(':visible:odd').addClass('even');
      });
      
      $('#user-person-search input').on('input', function(e){
        var searchString = $(this).val().toLowerCase();
        
        if(searchString.length > 0){
          $('#user-person-list .person').each(function(i){
            var fullname = $(this).data('fullname').toLowerCase();
            var lastname = $(this).data('lastname').toLowerCase();
            var username = $(this).data('username').toLowerCase();
            var email = $(this).data('email').toLowerCase();
            
            if(fullname.indexOf(searchString) === 0 || lastname.indexOf(searchString) === 0 || username.indexOf(searchString) === 0 || email.indexOf(searchString) === 0){
              $(this).removeClass('search-hidden');
            } else {
              $(this).addClass('search-hidden');
            }
          });
        } else {
          $('#user-person-list .person').removeClass('search-hidden');
        }
        
        $('#user-person-list .person').removeClass('even').filter(':visible:odd').addClass('even');;
      });
      
    });
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.simple', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>