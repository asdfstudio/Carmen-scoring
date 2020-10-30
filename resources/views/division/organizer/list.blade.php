@if(!$divisions->isEmpty())
    <ul class="list-group">
        @foreach($divisions as $division)
            <li class="list-group-item">{{ link_to_route('organizer.competition.division.show', $division->name, [$division->competition,$division]) }}
                {!! $division->status_label('pull-right') !!}
                <ul class="actions-group pull-right mv">
                    @php $scoringUrl = route('organizer.competition.division.scoring', [$division->competition, $division]); @endphp

                    @can('activateScoring', $division)
                        <li> {!! form($scoringForms['activate'], ['url' => $scoringUrl]) !!}</li>
                    @endcan

                    @can('reactivateScoring', $division)
                        <li> {!! form($scoringForms['reactivate'], ['url' => $scoringUrl]) !!}</li>
                    @endcan

                    @can('deactivateScoring', $division)
                        <li> {!! form($scoringForms['deactivate'], ['url' => $scoringUrl]) !!}</li>
                    @endcan

                    @can('completeScoring', $division)
                        <li> {!! form($scoringForms['complete'], ['url' => $scoringUrl]) !!}</li>
                    @endcan

                    @can('finalizeScoring', $division)
                        <li> {!! form($scoringForms['finalize'], ['url' => $scoringUrl]) !!}</li>
                    @endcan
                </ul>
            </li>
        @endforeach
    </ul>
@endif
