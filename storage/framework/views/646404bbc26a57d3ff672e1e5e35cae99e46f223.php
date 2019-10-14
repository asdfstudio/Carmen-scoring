<?php if($division->judges->isEmpty()): ?>
	<p>There are no judges. <?php echo e(link_to_route('organizer.competition.division.judge.create','Add one',[$division->competition,$division])); ?></p>
<?php endif; ?>

<?php if(!$division->judges->isEmpty()): ?>
<table class="table table-striped table-bordered">
  <tr>
  	<th>Judge Name</th>

    <?php foreach($captions as $caption): ?>
    <th><?php echo e($caption->name); ?></th>
    <?php endforeach; ?>

    <th>Edit</th>
  </tr>

  <?php foreach($judges as $judge): ?>
  <tr>

    <td><?php echo e(link_to_route('organizer.competition.division.judge.show',$judge->full_name, [$division->competition, $division, $judge])); ?></td>

    <?php foreach($captions as $caption): ?>
    <td>
    	<?php if(in_array($caption->id, $judge->captions->pluck('id')->toArray() )): ?>
    		<?php echo e($caption->name); ?>

      <?php endif; ?>
    </td>
    <?php endforeach; ?>

    <td><?php echo e(link_to_route('organizer.competition.division.judge.edit','Edit', [$division->competition, $division, $judge])); ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
