@extends('layouts.simple')

@section('content-header')

	<h1>{{ $choir->full_name }}</h1>

	<ul class="actions-group">
		<li>
			{{ link_to_route('organizer.competition.round.scores.show', 'Back to all ensembles', [$competition, $round], ['class' => 'action'])}}
		</li>
	</ul>
@endsection

@section('content')

	<h2>Penalties</h2>

	<!-- The organizers will need to assign/remove penalties after scores have been completed, but never after scores have been sent. -->
	@can('assignPenalty' , $division)
		{{ link_to_route('organizer.competition.division.penalty.choir.assign', 'Assign / Remove Penalties', [$competition->id, $division->id, $choir->id, 'redirect=choir_score'], ['class' => 'action'])}}
	@endcan

	<hr>

	@include('penalty.organizer.list', ['penalties' => $choir->penalties])

	@if($competition->organization->is_premium == 1)
	<h2>Upload Comments</h2>

	@include('recordings.list', ['competition' => $competition,'division'=>$division,'round'=> $round,'choir' => $choir , 'judgeList' => $judgeList])

	<hr>
	@endif

    <h2>Uploading box</h2>
    <div>
        {!! Form::open(array('route' => array('organizer.division-file.upload'), 'class' => 'dropzone', 'id' => 'myUploadingBox')) !!}
        {{ Form::hidden('division_id', $division->id) }}
        {{ Form::hidden('choir_id', $choir->id) }}
        {{ Form::hidden('round_id', $round->id) }}
        {!! Form::close() !!}

        <div class="recording-wrapper">
            @php
                $recordings =\App\Models\DivisionFile::query()->where('division_id', $division->id)->where('choir_id', $choir->id)->where('round_id', $round->id)->get();
                $recording_count = $recordings->count();
                $mode = 'player';
                $role = 'organizer';
               $canDelete = true;
            @endphp
            @include('division_file.list_file', ['canDelete' => $canDelete,'choir' => $choir, 'round' => $round, 'recordings' => $recordings, 'recording_count' => $recording_count, 'mode' => $mode, 'role' => $role])
        </div>
    </div>
	<h2>Scores</h2>

  @include('scores.organizer.choir_raw',['division' => $division])

@endsection

@push('own-scripts')
  <script src="{{asset('dist/js/vendor/dropzone.js')}}" type="text/javascript"></script>
  <script src="{{asset('dist/js/recording.js')}}" type="text/javascript"></script>
@endpush
