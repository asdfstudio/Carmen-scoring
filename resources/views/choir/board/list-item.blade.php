<li class="choir card list-group-item" data-resource-type="choir" data-resource-id="{{ $choir->id }}">

  @if($choir->school)
    <span class="school">{{ $choir->school->name }}</span>
  @endif

  <span class="name">{{ $choir->name }}</span>

  <div class="actions">
    @can('removeChoir', $division)
      <a class="remove-resource" data-resource-type="choir" data-resource-id="{{ $choir->id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.choir.destroy',[$division->competition,$division,$choir]) }}">Remove</a>
    @endcan
  </div>
</li>
