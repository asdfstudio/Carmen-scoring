@if($competitions->isEmpty())
	<p>There are no competitions.</p>
@endif

@if(!$competitions->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Competition Name</th>
    <th>City</th>
    <th>State</th>
    <th>Divisions</th>
    <th>Edit</th>
  </tr>

  @foreach($competitions as $competition)
  <tr>
  	<td>{{ link_to_route('judge.competition.show', $competition->name, [$competition]) }}</td>
    <td>@if($competition->place) {{ $competition->place->city }} @endif</td>
    <td>@if($competition->place) {{ $competition->place->state }} @endif</td>
    <td></td>
    <td>
    </td>
  </tr>
  @endforeach
</table>
</div>
@endif
