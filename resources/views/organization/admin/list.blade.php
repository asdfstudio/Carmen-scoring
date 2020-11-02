@if($organizations->isEmpty())
  <p>There are no organizations.</p>
@endif

@if(!$organizations->isEmpty())
<ul class="list-group">
  @foreach($organizations as $organization)
    <li class="list-group-item">
      {{ link_to_route('admin.organization.show', $organization->name, [$organization])}}
      @if(\Auth::user()->is_admin == 1)
        <label class="switch" >
          <input type="checkbox" @if($organization->vote_setting == 1) checked @endif>
            <span class="slider round audience-vote" data-id="{{ $organization->id }}" title="Audience vote on/off" data-href="{{ route('admin.organization.audience-vote', [$organization]) }}"></span>
            </label>

            <label class="switch" style="margin-right: 10px;">
              <input type="checkbox" @if($organization->is_premium == 1) checked @endif>
              <span class="slider round premium" data-id="{{ $organization->id }}" title="Enable/Disable Premium" data-href="{{ route('admin.organization.premium-status', [$organization]) }}"></span>
            </label>
    @endif
    </li>
  @endforeach
</ul>
@endif