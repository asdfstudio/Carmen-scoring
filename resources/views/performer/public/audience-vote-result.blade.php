<div class="table-wrapper-responsive">
  <table class="table table-striped table-bordered">
    <tr>
      <th>Category</th>
      <th>Choir</th>
      <th>Performer</th>
      <th>Vote</th>

    </tr>

    @foreach ($votes as $vote)
      <tr>
        <td>
          {!! $vote->performer->category_label('small') !!}
        </td>
        <td>
          @if($vote->performer->choir)
            {{ $vote->performer->choir->full_name }}
          @endif
        </td>
        <td>
          {{ link_to_route('organizer.competition.solo-division.performer.show', $vote->performer->name, [$competition, $soloDivision, $vote->performer]) }}
        </td>
        <td>
          {{ number_format($vote->vote_count, 0,'.',',') }}
        </td>
      </tr>
    @endforeach
  </table>
</div>
