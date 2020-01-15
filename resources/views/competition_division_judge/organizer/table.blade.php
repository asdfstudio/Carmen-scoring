@if($division->judges->isEmpty())
	<p>There are no judges. {{ link_to_route('organizer.competition.division.judge.create','Add one',[$division->competition,$division]) }}</p>
@endif

@if(!$division->judges->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
  	<th>Judge Name</th>

    @foreach($captions as $caption)
    <th>{{ $caption->name }}</th>
    @endforeach

    <th>Edit</th>
  </tr>

  @foreach($judges as $judge)
  <tr>

    <td>{{ link_to_route('organizer.competition.division.judge.show',$judge->full_name, [$division->competition, $division, $judge]) }}</td>

    @foreach($captions as $caption)
    <td>
    	@if(in_array($caption->id, $judge->captions->pluck('id')->toArray() ))
    		{{ $caption->name }}
      @endif
    </td>
    @endforeach

    <td>{{ link_to_route('organizer.competition.division.judge.edit','Edit', [$division->competition, $division, $judge]) }}</td>
  </tr>
  @endforeach
</table>
</div>
@endif
