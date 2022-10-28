<div class="division-bar body-width">
    <div class="heading">
        {{ link_to_route( Request::segment(1) . '.competition.round.show', $round->name, [$round->competition, $round])}}
        {!! $round->status_label() !!}
    </div>
    <div class="division-actions">
        <ul class="actions-group">
            <li>
                {{ link_to_route( Request::segment(1) . '.competition.show', 'All Rounds', [$round->competition], ['class' => 'action'])}}
            </li>
        </ul>
    </div>
</div>
<div class="division-navigation-bar body-width">
    <ul class="division-navigation">
        @can('update', $round)
        <li>
            @php $link_class = Request::segment(6) == 'edit' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.round.edit', [$round->competition, $round]) }}" class="{{ $link_class }}">Edit Scoring</a>
        </li>
        <li>
            @php $link_class = Request::segment(6) == 'board' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.round.board', [$round->competition, $round]) }}" class="{{ $link_class }}">Edit Judges</a>
        </li>
        @endcan
        @if($round->competition->organization->vote_setting)
        <li>
            @php $link_class = Request::segment(6) == 'audience' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.round.audience.index', [$round->competition, $round]) }}" class="{{ $link_class }}">Manage Audience Voting</a>
        </li>
        @endif
        @can('showAll', $round)
        <li>
            @php $link_class = Request::segment(6) == 'scores' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.round.scores.show', [$round->competition,$round]) }}" class="{{ $link_class }}">See Scores</a>
        </li>
        <li>
            <a href="{{ route('organizer.competition.round.index', [$round->competition]) }}">Back To All Rounds</a>
        </li>
        @endcan
        <li>
            @php $link_class = Request::segment(6) == 'award' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.round.award.index', [$round->competition, $round]) }}" class="{{ $link_class }}">Awards
                <span class="count">{{ $round->awards->count() }}</span>
            </a>
        </li>
    </ul>
</div>
