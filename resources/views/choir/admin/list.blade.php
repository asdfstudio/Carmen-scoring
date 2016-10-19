@if($choirs->isEmpty())
	<p>There are no choirs.</p>
@endif

@if(!$choirs->isEmpty())
<ul class="list-group">
  @foreach($choirs as $choir)
    <li class="choir list-group-item">

      @if($choir->school)
        <span class="school">{{ link_to_route('admin.school.show', $choir->school->name, [$choir->school]) }}</span>
      @endif

      <span class="name">{{ $choir->name }}</span>

      @if($choir->school AND $choir->school->place)
        <span class="location">{{ $choir->school->place->city_state() }}</span>
      @endif

      <ul class="actions-group">
        <li>{{ link_to_route('admin.choir.edit', 'Edit', [$choir], ['class' => 'action']) }}</li>
      </ul>



    </li>
  @endforeach
</ul>
@endif
