<li class="penalty card list-group-item" data-resource-type="penalty" data-resource-id="{{ $penalty->id }}">


  <span class="name">{{ $penalty->name }}</span>

  <div class="actions">
    @can('removeJudge', $division)
      <a class="remove-resource" data-resource-type="penalty" data-resource-id="{{ $penalty->id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.penalty.destroy',[$division->competition,$division,$penalty]) }}">Remove</a>
    @endcan
  </div>
</li>
