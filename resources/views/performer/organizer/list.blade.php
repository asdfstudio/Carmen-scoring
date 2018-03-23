<ul class="list-group">
  @foreach ($performers as $performer)
    <li class="choir list-group-item">
      @if($performer->choir)
        <span class="school">{{ $performer->choir->full_name }}</span>
      @endif

      <span class="name">{{ $performer->name }}</span>
      {!! $performer->category_label('small') !!}
  @endforeach
</ul>
