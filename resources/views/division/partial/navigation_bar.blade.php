<div class="division-bar body-width">
    <div class="heading">
        {{ link_to_route( Request::segment(1) . '.competition.division.show', $division->name, [$division->competition, $division])}}
        {!! $division->status_label() !!}
    </div>
<div class="division-actions">
    <ul class="actions-group">
        <li>
            {{ link_to_route( Request::segment(1) . '.competition.show', 'All Divisions', [$division->competition], ['class' => 'action'])}}
        </li>
    </ul>
</div>
</div>
<div class="division-navigation-bar body-width">
    <ul class="division-navigation">
        <li>
            @php $link_class = Request::segment(6) == 'edit' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.edit', [$competition, $division]) }}" class="{{ $link_class }}">Edit Division Settings</a>
        </li>
        <li>
            @php $link_class = Request::segment(6) == 'board' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.board', [$competition,$division]) }}" class="{{ $link_class }}">Edit Choirs and Judges</a>
        </li>
        <li>
            @php $link_class = Request::segment(6) == 'penalty' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.penalty.index', [$competition, $division]) }}" class="{{ $link_class }}">Penalties
                <span class="count">{{ $division->penalties->count() }}</span>
            </a>
        </li>
        <li>
            @php $link_class = Request::segment(6) == 'award' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.award.index', [$competition, $division]) }}" class="{{ $link_class }}">Awards
                <span class="count">{{ $division->awards->count() }}</span>
            </a>
        </li>
        <li>
            @php $link_class = Request::segment(6) == 'standing' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.standing.show', [$competition, $division]) }}" class="{{ $link_class }}">Final Standings</a>
        </li>
    </ul>
</div>
<div class="division-navigation-bar body-width">
    <ul class="division-navigation">
        <li>
            @php $link_class = in_array(Request::segment(6),['settings','edit']) ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.settings', [$competition, $division]) }}" class="{{ $link_class }}">Settings</a>
        </li>
        @if($division->competition->organization->vote_setting)
            <li>
                @php $link_class = Request::segment(6) == 'audience' ? 'active' : false; @endphp
                <a href="{{ route('organizer.competition.division.audience.index', [$competition, $division]) }}" class="{{ $link_class }}">Audience Vote
                </a>
            </li>
        @endif
        <li>
            @php $link_class = Request::segment(6) == 'penalty' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.penalty.index', [$competition, $division]) }}" class="{{ $link_class }}">Penalties
                <span class="count">{{ $division->penalties->count() }}</span>
            </a>
        </li>
        <li>
            @php $link_class = Request::segment(6) == 'award' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.award.index', [$competition, $division]) }}" class="{{ $link_class }}">Awards
                <span class="count">{{ $division->awards->count() }}</span>
            </a>
        </li>
        <li>
            @php $link_class = Request::segment(6) == 'standing' ? 'active' : false; @endphp
            <a href="{{ route('organizer.competition.division.standing.show', [$competition, $division]) }}" class="{{ $link_class }}">Final Standings</a>
        </li>

    </ul>
</div>
