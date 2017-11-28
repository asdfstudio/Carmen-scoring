<div class="board-list penalties" id="penalty-list">
  <div class="list-header">
    <h3>Penalties</h3>
    <span class="card-count" data-resource-type="penalty">{{ count($division->penalties) }}</span>
  </div>

  {!! form($newPenaltyForm) !!}

  @include('penalty.board.list')

  <a class="add-resource" data-resource-type="penalty" href="#">Add a penalty</a>
</div>
