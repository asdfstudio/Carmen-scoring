<li class="judge card list-group-item" data-resource-type="judge" data-resource-id="{{ $judge->id }}">

  <span class="name">{{ $judge->full_name }}</span>

  <ul class="captions-group">
    @php
      $str_judge_captions = "";
    @endphp
    @foreach($judge->captions as $caption)
      <li class="{{ $caption->background_css }} caption label">{{ $caption->name }}</li>
      @php
        $str_judge_captions .= $caption->id . '-';
      @endphp
    @endforeach
  </ul>
  <div class="actions">
    @can('removeJudge', $division)
      <a class="remove-resource" data-resource-type="judge" data-resource-id="{{ $judge->id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.judge.destroy',[$division->competition,$division,$judge]) }}">Remove</a>
      <a class="edit-resource" data-resource-type="judge" data-resource-id="{{ $judge->id }}" data-captions="{{ $str_judge_captions }}" data-all-captions="{{ $str_all_captions }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.judge.update',[$division->competition,$division,$judge]) }}">Edit captions</a>
    @endcan
  </div>
</li>
