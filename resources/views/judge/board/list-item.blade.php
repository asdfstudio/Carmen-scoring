<li class="judge card list-group-item" data-resource-type="judge" data-resource-id="{{ $judge->id }}">


  <span class="name">{{ $judge->full_name }}</span>

  <ul class="captions-group">
    @foreach($judge->captions as $caption)
      <li class="{{ $caption->background_css }} caption label">{{ $caption->name }}</li>
    @endforeach
  </ul>

  <div class="actions">
    @can('removeJudge', $division)
      <a class="remove-resource" data-resource-type="judge" data-resource-id="{{ $judge->id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.judge.destroy',[$division->competition,$division,$judge]) }}">Remove</a>
    @endcan
  </div>
</li>
