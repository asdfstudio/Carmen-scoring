
@if($judgeList)

   {!! Form::open(array('route' => array('organizer.competition.division.round.choir.recordings',$division->competition_id,$division,$round,$choir), 'method' => 'post')) !!}
    <div class="group">
    {{ Form::hidden('division_id', $division_id) }}
    {{ Form::hidden('choir_id', $choir_id) }}
    {{ Form::hidden('round_id', $round_id) }}
    @php if($judge_id) { $selected = $judge_id; } else {  $selected = false; }
    @endphp

    {{ Form::select('judge_id', $judgeList, $selected, ['placeholder' => 'Select a judge', 'class' => 'selectize']) }}
    {{ Form::submit('Submit', ['class' => 'btn btn-primary btn-md comment-submit-btn']) }}
    </div>
    {!! Form::close() !!}

    @if($judge_id != 'null' && $selected)
        {!! Form::open(array('route' => array('judge.recording.save'), 'class' => 'dropzone', 'id' => 'myAwesomeDropzone')) !!}
        {{ Form::hidden('division_id', $division_id) }}
        {{ Form::hidden('choir_id', $choir_id) }}
        {{ Form::hidden('round_id', $round_id) }}
        {{ Form::hidden('judge_id', $judge_id) }}
        {!! Form::close() !!}
    @endif

   <div class="container">

   @if(count($division->judges)>0)
        <div class="row wrap record-row">
            @foreach($division->judges[0]->recordings  as $key=>$recording)
            <div class="col-sm-6 record-item">
                <span class="record-span">{{$key + 1}}.</span>
                <div class="record-item-audio">
                        <audio controls> <source src="{{$recording->url}}"> </audio>
                        <span> {{$recording->created_at}} (UTC)</span>
                </div>
                <a class="delete record-span" id="delete-recording" onclick="deleteRecording({{$recording->id}})" href="javascript:void(0)"><i class="fa fa-trash" aria-hidden="true"></i></a>
            </div>

            @endforeach
        </div>
      @endif
    </div>

@endif
