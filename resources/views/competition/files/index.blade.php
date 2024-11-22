@extends('layouts.simple')

@section('title')
Uploaded Files for {{ $competition->name }} | @parent
@endsection

@section('content-header')
<h1>Uploaded Files for {{ $competition->name }}</h1>
@endsection

@section('content')
<h3>Uploaded Files</h3>
@if($groupedFiles->isEmpty())
    <p>No files have been uploaded for this competition.</p>
@else
    @foreach($groupedFiles as $roundId => $files)
        <div class="record-row">
            <h4>
                @if($roundId != 'No Round Assigned')
                    <strong>Round:</strong> {{ $files->first()->round->name }}
                @else
                    No Round Assigned
                @endif
            </h4>
            <br>
            @php
                $groupedBySchool = $files->groupBy(function ($file) {
                    return $file->choir ? $file->choir->school->name : 'No School Assigned';
                });
            @endphp
            @foreach($groupedBySchool as $schoolName => $choirFiles)
                <div class="list-group-item">
                    <h5><strong>School:</strong> {{ $schoolName }}</h5>
                    <ul class="list-group">
                        @foreach($choirFiles as $file)
                            <li class="list-group-item">
                                <div class="record-item">
                                    @if($file->type === 'recording')
                                        <span>Judge: {{ $file->judge ? $file->judge->full_name : 'No Judge Assigned' }}</span>
                                        <br>
                                        <br>
                                        <audio controls>
                                            <source src="{{ $file->url }}">
                                        </audio>
                                        <span>Uploaded on: {{ $file->created_at }} (UTC)</span>
                                    @elseif($file->type === 'division_file')
                                        <span>(Stage)</span>
                                        <br>
                                        <br>
                                        <audio controls>
                                            <source src="{{ $file->url }}">
                                        </audio>
                                        <span>Uploaded on: {{ $file->created_at }} (UTC)</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endforeach
@endif
@endsection