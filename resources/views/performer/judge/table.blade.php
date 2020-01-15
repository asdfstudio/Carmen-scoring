<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
    <th>Category</th>
    <th>Choir</th>
    <th>Performer</th>
    <th>Score</th>
    <th>Actions</th>

  </tr>

  @foreach ($performers as $performer)
    <tr>
      <td>
        {!! $performer->category_label('small') !!}
      </td>
      <td>
        @if($performer->choir)
          {{ $performer->choir->full_name }}
        @endif
      </td>
      <td>
        {{ $performer->name }}
      </td>
      <td>
        {{ $performer->score }}
      </td>
      <td>

        {{ link_to_route('judge.competition.solo-division.performer.edit','Edit performer', [$competition, $soloDivision, $performer], ['class' => 'action']) }}

        @if ($soloDivision->status_slug == 'active')
          {{ link_to_route('judge.competition.solo-division.performer.score','Enter Scores', [$competition, $soloDivision, $performer], ['class' => 'action']) }}
        @else
          {{ link_to_route('judge.competition.solo-division.performer.score','View Scores', [$competition, $soloDivision, $performer], ['class' => 'action']) }}
        @endif
      </td>
    </tr>
  @endforeach
</table>
</div>