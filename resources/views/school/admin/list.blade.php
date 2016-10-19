@if($schools->isEmpty())
	<p>There are no schools.</p>
@endif

@if(!$schools->isEmpty())
<ul class="list-group">
  @foreach($schools as $school)
    <li class="school list-group-item">

      <span class="name">{{ $school->name }}</span>

      @if($school->place)
        <span class="location">{{ $school->place->city_state() }}</span>
      @endif

      <ul class="actions-group">
        <li>{{ link_to_route('admin.school.edit', 'Edit', [$school], ['class' => 'action']) }}</li>
      </ul>



    </li>
  @endforeach
</ul>
@endif
