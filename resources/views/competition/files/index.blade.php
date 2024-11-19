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
                $groupedByChoir = $files->groupBy(function ($file) {
                    return $file->choir ? $file->choir->name : 'No Choir Assigned';
                });
            @endphp
            @foreach($groupedByChoir as $choirName => $choirFiles)
                <div class="list-group-item">
                    <h5><strong>Division:</strong> {{ $choirName }}</h5>
                    <ul class="list-group">
                        @foreach($choirFiles as $file)
                            <li class="list-group-item">
                                <div class="record-item">
                                    @if($file instanceof \App\Recording)
                                        <audio controls>
                                            <source src="{{ $file->url }}">
                                        </audio>
                                        <span>Uploaded on: {{ $file->created_at }} (UTC)</span>
                                    @elseif($file instanceof \App\Models\DivisionFile)
                                        <!-- <a href="{{ $file->url }}" target="_blank">{{ $file->name }}</a>
                                                            <span>Uploaded on: {{ $file->created_at }} (UTC)</span> -->
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