<ul class="judges cards list-group" data-resource-type="judge">
  @include('judge.board.list-item-prototype')

  @foreach($division->judges as $judge)
    @include('judge.board.list-item')
  @endforeach
</ul>
