@if($competition->divisions->isEmpty())
    <p>There are no divisions.</p>
@else
    <div class="table-wrapper-responsive">
        <table class="table table-striped table-bordered">
            <tr>
                <th>Name</th>
                <th>Edit</th>
                <th>Status</th>
                <th>Round</th>
                <th>Choirs</th>
                <th>Penalties</th>
                <th>Awards</th>
            </tr>

            @foreach($competition->divisions as $division)
                <tr>
                    <td>{{ link_to_route('organizer.competition.division.show', $division->name, [$competition, $division]) }}</td>
                    <td>
                        @can('update', $division)
                            {{ link_to_route('organizer.competition.division.edit', 'Edit', [$competition,$division]) }}
                        @endcan
                    </td>
                    <td>{!! $division->status_label('small') !!}</td>
                    <td>{{ link_to_route('organizer.competition.round.show', $division->round->name, [$competition,$division->round]) }}</td>
                    <td>
                        @can('update', $division)
                            @php $anchor = $division->choirs->count() > 0 ? $division->choirs->count() : 'Set Up';@endphp
                            {{ link_to_route('organizer.competition.division.board', $anchor, [$competition,$division]) }}
                        @endcan
                    </td>
                    <td>{{ $division->penalties->count() }}</td>
                    <td>{{ $division->awards->count() }}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif
