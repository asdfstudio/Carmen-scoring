@if($rounds->isEmpty())
    <p>There are no rounds.</p>
@endif

@if(!$rounds->isEmpty())
<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
    <th>Name</th>
    <th>Status</th>
    <th>Sheet</th>
    <th>Weighting</th>
    <th>Scoring</th>
  </tr>

  @foreach($rounds as $round)
  <tr>
    <td>{{ link_to_route('organizer.competition.round.show', $round->name, [$competition,$round]) }}</td>
    <td>{!! $round->status_label('small') !!}</td>
    <td>@if ($round->sheet){{ $round->sheet->name }} @endif</td>
    <td>@if ($round->captionWeighting){{ $round->captionWeighting->name }} @endif</td>
    <td>@if ($round->scoringMethod){{ $round->scoringMethod->name }} @endif</td>
  </tr>
  @endforeach
</table>
</div>
@endif
