<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
        }

        h1,
        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Recap Sheet for {{ $competitionName }}</h1>

    <!-- Custom styles for consistent column widths -->
    <style>
        .table th,
        .table td {
            padding: 8px;
            text-align: left;
        }

        .col-school {
            width: 18%;
        }

        .col-ensemble {
            width: 18%;
        }

        .col-type {
            width: 18%;
        }

        .col-class {
            width: 15%;
        }

        .col-score {
            width: 12%;
        }

        .col-ranking {
            width: 10%;
        }

        .col-rating {
            width: 10%;
        }

        .scoree.tiedd {
            color: white;
            background-color: red;
            padding: 2px 4px;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>

<!-- Recap Table -->
<table class="table">
    <thead>
        <tr>
            <th class="col-school">School Name</th>
            <th class="col-ensemble">Ensemble Name</th>
            <th class="col-ensemble">Director Name</th>
            <th class="col-type">Type</th>
            <th class="col-class">Class</th>
            <th class="col-score">Average Score</th>
            <th class="col-ranking">Ranking</th>
            <th class="col-rating">Rating</th>
        </tr>
    </thead>
    <tbody>
        @foreach($recapData as $data)
            @php
                $rank = 1;
                $previousRank = 0;
                $lastRankedScore = null;
                $limit = 3;
            @endphp

            @foreach($data['choirs'] as $i => $choir)
                @php
                    $nextChoir = $data['choirs'][$i + 1] ?? null;
                    $prevChoir = $data['choirs'][$i - 1] ?? null;

                    if ($choir['rank_checked'] == "0") {
                        $choir['ranking'] = "No Rank";
                    } else {
                        if (count($data['choirs']) === 1 || $rank > $limit) {
                            $choir['ranking'] = '-';
                        } else {
                            // Check if tied with the last ranked score
                            if ($lastRankedScore !== null && $choir['average_score'] == $lastRankedScore) {
                                $choir['ranking'] = $previousRank;
                            } else {
                                $choir['ranking'] = $rank;
                                $previousRank = $rank;
                                $lastRankedScore = $choir['average_score'];
                            }

                            // Peek ahead only for rank increment (ignore "No Rank" in next)
                            $nextValid = null;
                            for ($j = $i + 1; $j < count($data['choirs']); $j++) {
                                if ($data['choirs'][$j]['rank_checked'] != "0") {
                                    $nextValid = $data['choirs'][$j];
                                    break;
                                }
                            }

                            if (!$nextValid || $nextValid['average_score'] != $choir['average_score']) {
                                $rank++;
                            }
                        }
                    }

                    $isTied = false;
                    if ($choir['rank_checked'] == "1") {
                        $isTied = 
                            ($prevChoir && $prevChoir['rank_checked'] == "1" && $prevChoir['average_score'] == $choir['average_score']) ||
                            ($nextChoir && $nextChoir['rank_checked'] == "1" && $nextChoir['average_score'] == $choir['average_score']);
                    }
                @endphp

                <tr>
                    <td class="col-school">{{ $choir['school'] }}</td>
                    <td class="col-ensemble">{{ $choir['name'] }}</td>
                    <td class="col-ensemble">{{ $choir['director'] }}</td>
                    <td class="col-type">{{ $data['round'] }}</td>
                    <td class="col-class">{{ $data['division'] }}</td>
                    <td class="col-score">{{ $choir['average_score'] }}</td>
                    <td class="col-ranking">
                        @if($choir['rank_checked'] == "0")
                            No Rank
                        @elseif($isTied)
                            <span>{{ $choir['ranking'] }}</span>
                            <span class="scoree tiedd">tied</span>
                        @else
                            {{ $choir['ranking'] }}
                        @endif
                    </td>
                    <td class="col-rating">{{ $choir['rating'] }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>



<!-- Adjudicator Award Winners -->
@if(isset($adjudicatorWinners) && $adjudicatorWinners->isNotEmpty())
    <h2>Adjudicator Award Winners</h2>
    <table class="table">
        <thead>
            <tr>
                <th class="col-ensemble">Ensemble Name</th>
                <th class="col-ensemble">Director Name</th>
                <th class="col-type">Type</th>
                <th class="col-score">Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($adjudicatorWinners->sortBy('school') as $winner)
                <tr>
                    <td class="col-ensemble">{{ $winner['school'] }} “{{ $winner['name'] }}”</td>
                    <td class="col-ensemble">{{ $winner['director'] }}</td>
                    <td class="col-type">{{ $winner['round'] }}</td>
                    <td class="col-score">{{ $winner['average_score'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<!-- FOG Invitation Winners -->
@if(isset($fogInvitationWinners) && (!empty($fogInvitationWinners['choral']) || (isset($fogInvitationWinners['instrumental']) && $fogInvitationWinners['instrumental']->isNotEmpty())))
    <h2>Invitation Winners</h2>

    @if(!empty($fogInvitationWinners['choral']))
        <h3>Choral Invitation Winners</h3>
        <table class="table">
            <thead>
                <tr>
                    <th class="col-ensemble">Ensemble Name</th>
                    <th class="col-ensemble">Director Name</th>
                    <th class="col-type">Type</th>
                    <th class="col-score">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fogInvitationWinners['choral'] as $winner)
                    <tr>
                        <td class="col-ensemble">{{ $winner['school'] }} “{{ $winner['name'] }}”</td>
                        <td class="col-ensemble">{{ $winner['director'] }}</td>
                        <td class="col-type">{{ $winner['round'] }}</td>
                        <td class="col-score">{{ $winner['average_score'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(isset($fogInvitationWinners['instrumental']) && $fogInvitationWinners['instrumental']->isNotEmpty())
        <h3>Instrumental Invitation Winners</h3>
        <table class="table">
            <thead>
                <tr>
                    <th class="col-ensemble">Ensemble Name</th>
                    <th class="col-ensemble">Director Name</th>
                    <th class="col-type">Type</th>
                    <th class="col-score">Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fogInvitationWinners['instrumental'] as $winner)
                    <tr>
                        <td class="col-ensemble">{{ $winner['school'] }} “{{ $winner['name'] }}”</td>
                        <td class="col-ensemble">{{ $winner['director'] }}</td>
                        <td class="col-type">{{ $winner['round'] }}</td>
                        <td class="col-score">{{ $winner['average_score'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endif

<!-- Outstanding Group Awards Winners -->
@if($outstandingWinners['choral']->isNotEmpty())
    <h2>Outstanding Choral Group Winner</h2>
    <table class="table">
        <thead>
            <tr>
                <th class="col-ensemble">Ensemble Name</th>
                <th class="col-ensemble">Director Name</th>
                <th class="col-type">Type</th>
                <th class="col-score">Score</th>

            </tr>
        </thead>
        <tbody>
            @foreach($outstandingWinners['choral'] as $choral)
                <tr>
                    <td class="col-ensemble">{{ $choral['school'] }} “{{ $choral['name'] }}“</td>
                    <td class="col-ensemble">{{ $choral['director'] }}</td>
                    <td class="col-type">{{ $choral['round'] }}</td>
                    <td class="col-score">{{ $choral['average_score'] }} 
                        @if($choral['tied'])
                            <span class="scoree tiedd">tied</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if($outstandingWinners['band']->isNotEmpty())
    <h2>Outstanding Band Group Winner</h2>
    <table class="table">
        <thead>
            <tr>
                <th class="col-ensemble">Ensemble Name</th>
                <th class="col-ensemble">Director Name</th>
                <th class="col-type">Type</th>
                <th class="col-score">Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outstandingWinners['band'] as $band)
                <tr>
                    <td class="col-ensemble">{{ $band['school'] }} “{{ $band['name'] }}“</td>
                    <td class="col-ensemble">{{ $band['director'] }}</td>
                    <td class="col-type">{{ $band['round'] }}</td>
                    <td class="col-score">{{ $band['average_score'] }} @if($band['tied'])
                        <span class="scoree tiedd">tied</span>
                    @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if($outstandingWinners['orchestra']->isNotEmpty())
    <h2>Outstanding Orchestra Group Winner</h2>
    <table class="table">
        <thead>
            <tr>
                <th class="col-ensemble">Ensemble Name</th>
                <th class="col-ensemble">Director Name</th>
                <th class="col-type">Type</th>
                <th class="col-score">Score</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outstandingWinners['orchestra'] as $orchestra)
                <tr>
                    <td class="col-ensemble">{{ $orchestra['school'] }} “{{ $orchestra['name'] }}“</td>
                    <td class="col-ensemble">{{ $orchestra['director'] }}</td>
                    <td class="col-type">{{ $orchestra['round'] }}</td>
                    <td class="col-score">{{ $orchestra['average_score'] }} @if($orchestra['tied'])
                        <span class="scoree tiedd">tied</span>
                    @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif



<!-- Sweepstakes Winners -->
@if(isset($sweepstakesWinners['choral']))
    <h2>Choral Sweepstakes Winner</h2>
    <table class="table">
        <thead>
            <tr>
                <th class="col-school">School Name</th>
                <th class="col-ensemble">Winning Ensembles</th>
                <th class="col-ensemble">Director Name</th>
                <th class="col-score">Score</th>
                <th class="col-score">Combined Score</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-school">{{ $sweepstakesWinners['choral']['school_name'] }}</td>
                <td class="col-ensemble">
                    @foreach($sweepstakesWinners['choral']['choirs'] as $choirName)
                        <div>{{ $choirName }}</div>
                    @endforeach
                </td>
                <td class="col-ensemble">
                    @foreach($sweepstakesWinners['choral']['directors'] as $dirName)
                        <div>{{ $dirName }}</div>
                    @endforeach
                </td>
                <td class="col-score">
                    @foreach($sweepstakesWinners['choral']['average_score'] as $Score)
                        <div>
                            {{ $Score }}
                            @if($sweepstakesWinners['choral']['is_tied']) 
                                <span class="scoree tiedd">tied</span>
                            @endif
                        </div>
                    @endforeach
                </td>
                <td class="col-score">
                    {{ $sweepstakesWinners['choral']['total_score'] }}
                </td>
            </tr>
        </tbody>
    </table>
@endif


@if(isset($sweepstakesWinners['instrumental']))
    <h2>Instrumental Sweepstakes Winner</h2>
    <table class="table">
        <thead>
            <tr>
                <th class="col-school">School Name</th>
                <th class="col-ensemble">Winning Ensembles</th>
                <th class="col-ensemble">Director Name</th>
                <th class="col-score">Score</th>
                <th class="col-score">Combined Score</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-school">{{ $sweepstakesWinners['instrumental']['school_name'] }}</td>
                <td class="col-ensemble">
                    @foreach($sweepstakesWinners['instrumental']['choirs'] as $choirName)
                        <div>{{ $choirName }}</div>
                    @endforeach
                </td>
                <td class="col-ensemble">
                    @foreach($sweepstakesWinners['instrumental']['directors'] as $dirName)
                        <div>{{ $dirName }}</div>
                    @endforeach
                </td>
                <td class="col-score">
                    @foreach($sweepstakesWinners['instrumental']['average_score'] as $index => $Score)
                        <div>{{ $Score }} 
                            @if(isset($sweepstakesWinners['instrumental']['is_tied']) && $sweepstakesWinners['instrumental']['is_tied']) 
                                <span class="scoree tiedd">tied</span>
                            @endif
                        </div>
                    @endforeach
                </td>
                <td class="col-score">
                    {{ $sweepstakesWinners['instrumental']['total_score'] }}
                </td>
            </tr>
        </tbody>
    </table>
@endif


@if(isset($sweepstakesWinners['festival']))
    <h2>Festival Sweepstakes Winner</h2>
    <table class="table">
        <thead>
            <tr>
                <th class="col-school">School Name</th>
                <th class="col-ensemble">Winning Ensembles</th>
                <th class="col-ensemble">Director Name</th>
                <th class="col-score">Score</th>
                <th class="col-score">Combined Score</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-school">{{ $sweepstakesWinners['festival']['school_name'] }}</td>
                <td class="col-ensemble">
                    @foreach($sweepstakesWinners['festival']['choirs'] as $choirName)
                        <div>{{ $choirName }}</div>
                    @endforeach
                </td>
                <td class="col-ensemble">
                    @foreach($sweepstakesWinners['festival']['directors'] as $dirName)
                        <div>{{ $dirName }}</div>
                    @endforeach
                </td>
                <td class="col-score">
                    @foreach($sweepstakesWinners['festival']['average_score'] as $Score)
                        <div>{{ $Score }}
                            @if($sweepstakesWinners['festival']['is_tied'])
                            <span class="scoree tiedd">tied</span>
                        </div>
                    @endif
                    @endforeach
                </td>
                <td class="col-score">
                    {{ $sweepstakesWinners['festival']['total_score'] }}
                </td>
            </tr>
        </tbody>
    </table>
@endif


</body>

</html>