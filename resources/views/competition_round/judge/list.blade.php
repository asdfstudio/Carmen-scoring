<ul class="list-group">
    <li class="round list-group-item">
        <span class="name">{{ link_to_route('judge.round.scores.summary', $round->name, [$round->competition,$division,$round]) }}</span>
        <span class="label status {{ $round->status_slug() }}">{{ $round->status() }}</span>
    </li>
</ul>
