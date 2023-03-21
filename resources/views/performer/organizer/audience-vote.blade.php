<div class="table-wrapper-responsive">
  <table class="table table-striped table-bordered">
    <tr>
      <th>Category</th>
      <th>Ensembles</th>
      <th>Performer</th>
      <th>Vote</th>
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

       <td>
         @php $votesObject = isset($audience)?json_decode($performer->votes($audience->id)):NULL; @endphp
         @if(NULL === $votesObject)
           0
         @else
           {{ number_format($votesObject->vote_count) }}
         @endif
       </td>
      </tr>
    @endforeach
  </table>
</div>
