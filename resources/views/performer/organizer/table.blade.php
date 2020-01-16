<div class="table-wrapper-responsive">
<table class="table table-striped table-bordered">
  <tr>
    <th>Category</th>
    <th>Choir</th>
    <th>Performer</th>
    <th>Score</th>
    <th>Place</th>

    @if (!empty($judges))
      @foreach ($judges as $judge)
        <th>{{ $judge->full_name }}</th>
      @endforeach
    @endif

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
        {{ link_to_route('organizer.competition.solo-division.performer.show', $performer->name, [$competition, $soloDivision, $performer]) }}
      </td>
      <td>{{ $performer->score }}</td>
      <td>{{ $performer->rank }}</td>

      @if (!empty($judges))
        @foreach ($judges as $judge)
          <td>{{ $performer->judgeScores[$judge->id] }}</td>
        @endforeach
      @endif
    </tr>
  @endforeach
</table>
</div>