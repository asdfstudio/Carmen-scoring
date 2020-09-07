<li class="judge card list-group-item" data-resource-type="judge" data-resource-id="{{ $judge->id }}">

  <div class="dg-card-dec1"></div>

  <div class="text-center dg-mb-12 name-div"><span class="name text-center">{{ $judge->full_name }}</span></div>

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
      <a class="remove-resource" data-resource-type="judge" data-resource-id="{{ $judge->id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.judge.destroy',[$division->competition,$division,$judge]) }}">
        <i class="fa fa-trash"></i>
        Remove
      </a>
      <a class="edit-resource dg-mr-28" data-resource-type="judge" data-resource-id="{{ $judge->id }}" data-captions="{{ $str_judge_captions }}" data-all-captions="{{ $str_all_captions }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.judge.update',[$division->competition,$division,$judge]) }}">
        <i class="fa fa-pencil"></i>
        Edit
      </a>
    @endcan
  </div>
</li>
