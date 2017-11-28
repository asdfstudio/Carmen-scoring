<div class="board-list rounds" id="round-list">
  <div class="list-header">
    <h3>Rounds</h3>
    <span class="card-count" data-resource-type="round">{{ count($division->rounds) }}</span>
  </div>

  {!! form($newRoundForm) !!}

  @include('round.board.list')

  <a class="add-resource" data-resource-type="round" href="#">Add a round...</a>
</div>
