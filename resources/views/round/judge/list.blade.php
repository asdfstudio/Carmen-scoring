@if(!$rounds->isEmpty())
    <ul class="list-group">
        @foreach($rounds as $round)
            <li class="list-group-item round">

                {{ link_to_route('judge.round.scores.spreadsheet', $round->name, [$competition, $round], ['class' => 'name']) }}

                {!! $round->status_label('pull-right') !!}

            </li>
        @endforeach
    </ul>
@endif
