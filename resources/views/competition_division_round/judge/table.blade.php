@if($division->rounds->isEmpty())
	<p>There are no rounds.</p>
@endif

@if(!$division->rounds->isEmpty())
<table class="table table-striped table-bordered">
  <tr>
    <th>Name</th>
    <th>Scoring Status</th>
  </tr>
  
  @foreach($division->rounds as $round)
  <tr>
  	
    <td>{{ link_to_route('round.scores',$round->name,[$division->competition,$division,$round]) }}</td>
    <td>{{ $round->status() }}</td>
  </tr>
  @endforeach
</table>
@endif