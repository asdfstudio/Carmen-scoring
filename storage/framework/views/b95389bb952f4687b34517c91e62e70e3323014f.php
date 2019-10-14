<?php if($captions->isEmpty()): ?>
	<p>There are no captions.</p>
<?php endif; ?>

<?php if(!$captions->isEmpty()): ?>
<ul class="list-group">
  <?php foreach($captions as $caption): ?>
    <li class="caption list-group-item <?php echo e($caption->border_left_css); ?>">
			<!--<span class="color_swatch" style="background-color:<?php echo e($caption->hex_color); ?>"></span>-->
      <span class="name"><?php echo e($caption->name); ?></span>
      <ul class="actions-group">
        <li><?php echo e(link_to_route('admin.caption.edit', 'Edit caption', [$caption], ['class' => 'action'])); ?></li>
      </ul>

    </li>
  <?php endforeach; ?>
</ul>
<?php endif; ?>
