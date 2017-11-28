<div class="board-list judges" id="judge-list">
  <div class="list-header">
    <h3>Judges</h3>
    <span class="card-count" data-resource-type="judge">{{ count($division->judges) }}</span>
  </div>

  {!! form($newJudgeForm) !!}

  @include('judge.board.list')

  <a class="add-resource" data-resource-type="judge" href="#">Add a judge</a>
</div>
