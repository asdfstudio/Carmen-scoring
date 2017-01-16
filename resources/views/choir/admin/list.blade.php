@if($choirs->isEmpty())
	<p>There are no choirs.</p>
@endif

@if(!$choirs->isEmpty())
<ul class="list-group">
  @foreach($choirs as $choir)
    <li class="choir list-group-item">

      @if($choir->school)
        <span class="school">{{ link_to_route('admin.school.edit', $choir->school->name, [$choir->school]) }}</span>
      @endif

      <span class="name">{{ link_to_route('admin.choir.show', $choir->name, [$choir]) }}</span>

      @if($choir->school AND $choir->school->place)
        <span class="location">{{ $choir->school->place->city_state() }}</span>
      @endif

			@if($choir->directors->count() > 0)
				<div class="">
					Director:
					{{ $choir->directors->pluck('full_name')->implode(', ') }}
				</div>
			@endif

      <ul class="actions-group">
        <li>{{ link_to_route('admin.choir.edit', 'Edit', [$choir], ['class' => 'action']) }}</li>
				<li>{{ link_to_route('admin.choir.show', 'Manage Directors', [$choir], ['class' => 'action']) }}</li>
      </ul>



    </li>
  @endforeach
</ul>
@endif
