<li class="card-prototype card judge list-group-item" data-resource-type="judge" data-resource-id="@{{id}}">

  <span class="name">@{{first_name}} @{{last_name}}</span>

  <ul class="captions-group">
    @{{ #captions }}
      <li class="background-color-@{{ color_id }} caption label">@{{ name }} </span>
    @{{ /captions }}
  </ul>

  <div class="actions">
    @can('removeJudge', $division)
      <!-- <a class="remove-judge" href="#">Remove</a> -->
      <a class="remove-resource" data-resource-type="judge" data-resource-id="@{{ id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.division.show',[$division->competition,$division]) }}/judge/@{{id}}">Remove</a>
      <!-- <a class="remove-judge" href="#">Edit captions</a> -->
      <a class="edit-resource" data-resource-type="judge" data-resource-id="@{{ id }}"  data-csrf-token="{{ csrf_token() }}" data-captions="@{{ captions_join }}" data-all-captions="{{ $str_all_captions }}" href="{{ route('organizer.competition.division.show',[$division->competition,$division]) }}/judge/@{{id}}">Edit captions</a>
    @endcan
  </div>
</li>
