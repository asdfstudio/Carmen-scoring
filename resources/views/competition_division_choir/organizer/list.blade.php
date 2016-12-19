@if($choirs->isEmpty())
	<p>There are no choirs.</p>
@endif

@if(!$choirs->isEmpty())
<ul class="list-group">
  @foreach($choirs as $choir)
	  <li class="choir list-group-item">
      @if($choir->school)
        <span class="school">{{ $choir->school->name }}</span>
      @endif

			<span class="name">{{ $choir->name }}</span>

      @if($choir->school AND $choir->school->place AND $choir->school->place->city_state())
        <span class="location">{{ $choir->school->place->city_state() }} </span>
      @endif

			@can('removeChoir', $division)
				<div class="actions-group">
					{!! form($deleteForm, ['url' => route('organizer.competition.division.choir.destroy',[$division->competition,$division,$choir])]) !!}
				</div>
			@endcan

		</li>
  @endforeach
</ul>
@endif
