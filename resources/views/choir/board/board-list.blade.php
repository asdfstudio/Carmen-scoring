<div class="board-list choirs" id="choir-list">
  <div class="list-header">
    <h3>Ensembles</h3>
    <span class="card-count" data-resource-type="choir">{{ count($division->choirs) }}</span>
  </div>

  @if($division->status_slug() != 'finalized' AND $division->status_slug() != 'completed')
    <a class="add-resource" data-resource-type="choir" href="#">Add an ensemble</a>
  @endif

  {!! form($newChoirForm) !!}

  @include('choir.board.list')

</div>
