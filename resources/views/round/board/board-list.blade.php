<div class="board-list rounds" id="round-list">
  <div class="list-header">
    <h3>Types</h3>
    <span class="card-count" data-resource-type="round">{{ count($competition->rounds) }}</span>
  </div>

  <a class="add-resource" data-resource-type="round" href="#">Add a type...</a>

  {!! form($newRoundForm) !!}

  @include('round.board.list')

</div>
