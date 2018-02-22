@extends('layouts.simple')

@section('content-header')
  <h1>Raw Score Logs</h1>

@endsection

@section('content')

  @include('raw_score_log.admin.list')

@endsection
