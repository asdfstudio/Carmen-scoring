<ul class="penalties cards list-group" data-resource-type="penalty">
  @include('penalty.board.list-item-prototype')

  @foreach($division->penalties as $penalty)
    @include('penalty.board.list-item')
  @endforeach
</ul>
