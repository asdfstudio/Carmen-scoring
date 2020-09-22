@if(!$rounds->isEmpty())
    @foreach($rounds as $round)
        <ul class="list-group">
        <li class="list-group-item">Round: {{ $round->name }}
        <ul class="list-group">
          @foreach($round->divisions as $division)
              <li class="list-group-item">{{ link_to_route('organizer.competition.division.show', $division->name, [$division->round->competition,$division]) }}
                {!! $division->status_label('pull-right') !!}
            </li>
          @endforeach
        </ul>
        </li>
    @endforeach
    </ul>
@endif
