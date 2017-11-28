<li class="round card" data-resource-type="round" data-resource-id="{{ $round->id }}">
  <div class="name">{{ $round->name }}</div>

  <div class="actions">
    <!--<a class="edit-resource" data-resource-type="round" data-resource-id="{{ $round->id }}" href="#" data-resource="{{ json_encode($round) }}">Edit</a>
    <a class="remove-resource" data-resource-type="round" data-resource-id="{{ $round->id }}" href="{{ route('organizer.competition.division.round.destroy',[$division->competition,$division,$round]) }}">Remove </a>-->
  </div>
</li>
