<?php if($people): ?>
  <ul class="list-group">
    <?php foreach($people as $person): ?>
      <li class="list-group-item person">
        <span class="name"><?php echo e($person->full_name); ?></span>
        <span class="email"><?php echo e($person->email); ?></span>
        <span class="tell"><?php echo e($person->tel); ?></span>

        <?php echo e(link_to_route('admin.choir.director.edit', 'Edit', [$choir, $person], ['class' => 'action pull-right'])); ?>

      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
