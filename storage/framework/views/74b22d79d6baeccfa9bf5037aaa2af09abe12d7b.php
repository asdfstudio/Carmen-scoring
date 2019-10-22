<?php if($division->standing == false): ?>
  <p>
    There are no final standings yet.
  </p>
<?php endif; ?>



<?php if($division->standing): ?>

  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('viewFinalStandings', $division)): ?>

    <?php if($division->standing->is_consensus_scoring): ?>
      <p class="alert alert-warning">
        Consensus scoring is used for this division.
      </p>
    <?php endif; ?>

    <?php echo $__env->make('standing.list', ['standing' => $division->standing], \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>

  <?php endif; ?>

  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->denies('viewFinalStandings', $division)): ?>
    <p>
      You will be able to view the standings once they are finalized.
    </p>
  <?php endif; ?>

<?php endif; ?>
