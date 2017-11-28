<ul class="rounds cards" data-resource-type="round">
  @include('round.board.list-item-prototype')

  @foreach($division->rounds as $round)
    @include('round.board.list-item')
  @endforeach
</ul>
