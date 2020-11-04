<div class="d-none all-sheets-detail-wrapper">
    @foreach ($sheets as $sheet)
        <div class="sheet-detail-wrapper sheet-id-{{$sheet->id}} text-left">
            <h1 class="text-center">{{ $sheet->name }}</h1>

            <h4 class="text-center">Criteria: {{ count($sheet->criteria) }}</h4>
            <h4 class="text-center">Total Points Available: {{ $sheet->max_score }}</h4>
            <h4 class="text-center">Total Weighted Points Available: {{ $sheet->weighted_max_score }}</h4>

            @foreach ($sheet->captions as $caption)
                <h2>{{ $caption->name }}</h2>

                @php
                    $criteria = $sheet->criteria->where('caption_id', $caption->id);
                @endphp

                @if($criteria->isEmpty())
                    <p>There are no criteria.</p>
                @endif

                @if(!$criteria->isEmpty())
                    <ul class="list-group">
                        @foreach($criteria as $criterion)
                            <li class="school list-group-item">

                                <div class="name">
                                    {{ $criterion->name }}
                                    <div class="pull-right label">Max score: {{ $criterion->max_score }}</div>
                                </div>
                                <div class="description mv">{{ $criterion->description }}</div>

                            </li>
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </div>
    @endforeach
</div>
