@if($commentUrls->isEmpty())
	<p>There are no feedback URLs yet</p>
@endif

@if(!$commentUrls->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
  	<th>Choir</th>
    <th>URL</th>
  </tr>

  @foreach ($commentUrls as $commentUrl)
    <tr>
      <td>{{ $commentUrl->choir->full_name }}</td>
      <td>{{ link_to_route('feedback.show', null, $commentUrl->access_code, ['target' => '_blank']) }}</td>
    </tr>
  @endforeach
</table>
@endif
