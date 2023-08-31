<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    html {
        -webkit-print-color-adjust: exact;
    }
    body {
        font-family: "Lato", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 14px;
        margin: 0px;
    }
    table {
        border-spacing: 0;
    }

    .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    }

    .table-wrapper-responsive table {
        border-collapse: separate;
    }
    .table > tbody > tr.align-bottom th, .table > tbody > tr.align-bottom td {
        vertical-align: bottom;
    }
    .table-bordered {
        border: 1px solid #ddd;
    }

    .caption-header {
        font-size: 1.2em;
        color: #FFF;
        background: #706f6f;
    }

    .table-wrapper-responsive .table-striped > tbody > tr.caption-header > th:first-child {
        border-right: 0 !important;
    }

    .table-wrapper-responsive .table-striped > tbody > tr.caption-header > th:last-child {
        border-left: 0 !important;
    }

    th {
        text-align: left;
    }

    .table > thead > tr > th, .table > thead > tr > td, .table > tbody > tr > th, .table > tbody > tr > td, .table > tfoot > tr > th, .table > tfoot > tr > td {
        padding: 3px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
    }

    .table-bordered > thead > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > tfoot > tr > td {
        border: 1px solid #ddd;
    }

    .background-color-1 {
        background: #8d4198 !important;
    }

    .background-color-2 {
        background: #33b24d !important;
    }

    .background-color-3 {
        background: #f79523 !important;
    }

    .background-color-4 {
        background: #0985eb !important;
    }

    .background-color-5 {
        background: #80506e !important;
    }

    .background-color-6 {
        background: #4c9982 !important;
    }

    .background-color-7 {
        background: #e5a817 !important;
    }

    .background-color-8 {
        background: #5189b8 !important;
    }

    .score.penalty {
        color: #CA2128;
    }

    .score {
        padding: 0 3px;
    }

    .score.penalty:before {
        content: "(";
    }

    .score.penalty:after {
        content: ")";
    }
    .score.tied:after {
        content: 'tied';
        display: inline-block;
        padding: 0 4px;
        margin-left: 5px;
        font-size: 12px;
        font-weight: 400;
        color: #ffffff;
        border-radius: 4px;
        background: #ee191c;
    }
    .weighted {
        display: none;
    }

    .raw {
        display: none;
    }

    .rank {
        display: none;
    }
    .average {
        display: none;
    }
    .score.total {
        font-weight: bold;
    }
    a {
        color: #7F4091;
        text-decoration: none;
    }
    @if($typePdf === 'raw')
        span.score.raw, input.score.raw {
        display: inline !important;
    }

    table.scoreboard.toggle-scores.raw, table.scoreboard.toggle-scores.score.raw {
        display: table !important;
    }

    th.total_column.raw, td.total_column.raw {
        display: table-cell !important;
    }
    @endif
        @if($typePdf === 'weighted')
        span.score.weighted, input.score.weighted {
        display: inline !important;
    }

    table.scoreboard.toggle-scores.weighted, table.scoreboard.toggle-scores.score.weighted {
        display: table !important;
    }

    th.total_column.weighted, td.total_column.weighted {
        display: table-cell !important;
    }
    @endif

     @if($typePdf === 'average')
        span.score.average, input.score.average {
        display: inline !important;
    }

    table.scoreboard.toggle-scores.average, table.scoreboard.toggle-scores.score.average {
        display: table !important;
    }

    th.total_column.average, td.total_column.average {
        display: table-cell !important;
    }
    @endif
       @if($typePdf === 'rank')
        span.score.rank, input.score.rank {
        display: inline !important;
    }

    table.scoreboard.toggle-scores.rank, table.scoreboard.toggle-scores.score.rank {
        display: table !important;
    }

    th.total_column.rank, td.total_column.rank {
        display: table-cell !important;
    }
    @endif
    .sideways-header {
        display: flex;
        flex-grow: 1;
        height: 120px;
    }
    .sideways-header a, .sideways-header span {
        display: inline;
        transform: rotate(180deg);
        -ms-writing-mode: tb-rl;
        writing-mode: vertical-rl;
    }
</style>
<h1>{{$round->name}}</h1>
@if($typePdf === 'condorcet')
    @include('pdf_score.organizer.ranked_condorcet',['choirs' => $choirs, 'judges' => $judges])
@endif
