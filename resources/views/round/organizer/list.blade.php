@if(!$rounds->isEmpty())
    <ul class="list-group">
    @foreach($rounds as $round)
      <li class="list-group-item">{{ link_to_route('organizer.competition.round.show', $round->name, [$round->competition, $round]) }}
        {!! $round->status_label('pull-right') !!}
        </li>
    @endforeach
    </ul>
@endif
