@extends('layouts.simple')

@section('content-header')
  <h1>Raw Score Log - {{ $date }}</h1>

  <ul class="actions-group">
    <li>{{ link_to_route('admin.raw-score-log.index', 'Back to logs', [], ['class' => 'action']) }}</li>
  </ul>
@endsection

@section('content')

  <pre>{{ $fileContents }}</pre>


@endsection
