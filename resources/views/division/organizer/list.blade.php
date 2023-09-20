@if(!$divisions->isEmpty())
    <style>
        .list-divisions button[disabled] {
            background: #EFEFEF;
            cursor: not-allowed;
        }
    </style>
    <ul class="list-group list-divisions">
        @foreach($divisions as $division)
            <li class="list-group-item">{{ link_to_route('organizer.competition.division.show', $division->name, [$division->competition,$division]) }}
                {!! $division->status_label('pull-right') !!}
                <ul class="actions-group pull-right mv">
                    @php $scoringUrl = route('organizer.competition.division.scoring', [$division->competition, $division]); @endphp

                    <li class="division-{{$division->id}}-activeScoring"> {!! form($scoringForms['activate'], ['url' => $scoringUrl]) !!}</li>
                    <script>
                        {
                            let shouldDisableActivate = false;
                            @cannot('activateScoring', $division)
                                shouldDisableActivate = true
                            @endcan
                            if (shouldDisableActivate) {
                                document.querySelector('.division-{{ $division->id }}-activeScoring').querySelector('[type="submit"]').disabled = true;
                            }
                        }
                    </script>
                    <li class="division-{{$division->id}}-reactivateScoring"> {!! form($scoringForms['reactivate'], ['url' => $scoringUrl]) !!}</li>
                    <script>
                        {
                            let shouldDisableActivate = false;
                            @cannot('reactivateScoring', $division)
                                shouldDisableActivate = true
                            @endcan
                            if (shouldDisableActivate) {
                                document.querySelector('.division-{{ $division->id }}-reactivateScoring').querySelector('[type="submit"]').disabled = true;
                            }
                        }
                    </script>
                    <li class="division-{{$division->id}}-deactivateScoring"> {!! form($scoringForms['deactivate'], ['url' => $scoringUrl]) !!}</li>
                    <script>
                        {
                            let shouldDisableActivate = false;
                            @cannot('deactivateScoring', $division)
                                shouldDisableActivate = true
                            @endcan
                            if (shouldDisableActivate) {
                                document.querySelector('.division-{{ $division->id }}-deactivateScoring').querySelector('[type="submit"]').disabled = true;
                            }
                        }
                    </script>
                    <li class="division-{{$division->id}}-completeScoring"> {!! form($scoringForms['complete'], ['url' => $scoringUrl]) !!}</li>
                    <script>
                        {
                            let shouldDisableActivate = false;
                            @cannot('completeScoring', $division)
                                shouldDisableActivate = true
                            @endcan
                            if (shouldDisableActivate) {
                                document.querySelector('.division-{{ $division->id }}-completeScoring').querySelector('.action').disabled = true;
                            }
                        }
                    </script>
                    <li class="division-{{$division->id}}-finalizeScoring"> {!! form($scoringForms['finalize'], ['url' => $scoringUrl]) !!}</li>
                    <script>
                        {
                            let shouldDisableActivate = false;
                            @cannot('finalizeScoring', $division)
                                shouldDisableActivate = true
                            @endcan
                            if (shouldDisableActivate) {
                                document.querySelector('.division-{{ $division->id }}-finalizeScoring').querySelector('.action').disabled = true;
                            }
                        }
                    </script>
                </ul>
            </li>
        @endforeach
    </ul>
@endif
