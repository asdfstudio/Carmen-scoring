@if($organizations->isEmpty())
  <p>There are no organizations.</p>
@endif

@if(!$organizations->isEmpty())
<ul class="list-group">
  @foreach($organizations as $organization)
    <li class="list-group-item">
      {{ link_to_route('admin.organization.show', $organization->name, [$organization])}}
      @if(\Auth::user()->is_admin == 1)
      
    <label class="switch">
  <input type="checkbox" @if($organization->is_premium == 1) checked @endif>
  <span class="slider round" data-id="{{ $organization->id }}"></span>
</label>
    @endif
    </li>
  @endforeach
</ul>
@endif