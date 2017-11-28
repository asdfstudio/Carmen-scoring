<ul class="choirs cards list-group" data-resource-type="choir">
  @include('choir.board.list-item-prototype')

  @foreach($division->choirs as $choir)
    @include('choir.board.list-item')
  @endforeach
</ul>
