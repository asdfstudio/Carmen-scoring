@if($organizations->isEmpty())
	<p>There are no organizations.</p>
@endif

@if(!$organizations->isEmpty())
<ul class="list-group">
  @foreach($organizations as $organization)
    <li class="list-group-item">
      {{ link_to_route('admin.organization.show', $organization->name, [$organization])}}
    </li>
  @endforeach
</ul>
@endif
