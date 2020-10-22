<li class="card-prototype card judge list-group-item" data-resource-type="judge" data-resource-id="@{{id}}">

  <div class="dg-card-dec1"></div>

  <div class="text-center dg-mb-12 name-div"><span class="name text-center">@{{first_name}} @{{last_name}}</span></div>

  <ul class="captions-group">
    @{{ #captions }}
      <li class="background-color-@{{ color_id }} caption label">@{{ name }} </span>
    @{{ /captions }}
  </ul>

  <div class="actions">
    @can('removeJudge', $round)
      <a class="remove-resource" data-resource-type="judge" data-resource-id="@{{ id }}" data-csrf-token="{{ csrf_token() }}" href="{{ route('organizer.competition.round.show',[$round->competition,$round]) }}/judge/@{{id}}">
        <i class="fa fa-trash"></i>
        Remove
      </a>
      <a class="edit-resource dg-mr-28" data-resource-type="judge" data-resource-id="@{{ id }}"  data-csrf-token="{{ csrf_token() }}" data-captions="@{{ captions_join }}" data-all-captions="{{ $str_all_captions }}" href="{{ route('organizer.competition.division.show',[$round->competition,$round]) }}/judge/@{{id}}">
        <i class="fa fa-pencil"></i>
        Edit
      </a>
    @endcan
  </div>
</li>
