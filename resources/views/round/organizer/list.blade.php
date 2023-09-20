@if(!$rounds->isEmpty())
    <ul class="list-group">
    @foreach($rounds as $round)
      <li class="list-group-item">{{ link_to_route('organizer.competition.round.show', $round->name, [$round->competition, $round]) }}
        {!! $round->status_label('pull-right') !!}
          <div style="margin-top: 10px">
              @include('division.organizer.list',['divisions' => $round->divisions, 'scoringForms' => $divisionScoringForms])
          </div>
        </li>
    @endforeach
    </ul>
@endif
