@if($division->choirs->isEmpty())
	<p>There are no choirs. {{ link_to_route('organizer.competition.division.choir.create','Add one',[$division->competition,$division]) }}</p>
@endif

@if(!$division->choirs->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>School</th>
    <th>Choir Name</th>
    <th>City</th>
    <th>State</th>
  </tr>

  @foreach($division->choirs as $choir)
  <tr>

    <td>@if($choir->school) {{ $choir->school->name }} @endif</td>
    <td>{{ link_to_route('organizer.competition.division.choir.show',$choir->name,[$division->competition,$division,$choir]) }}</td>
    <td>@if($choir->school AND $choir->school->place) {{ $choir->school->place->city }} @endif</td>
    <td>@if($choir->school AND $choir->school->place) {{ $choir->school->place->state }} @endif</td>
  </tr>
  @endforeach
</table>
</div>
@endif
