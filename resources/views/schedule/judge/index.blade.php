@extends('layouts.simple')

@section('content')

  @include('schedule.judge.table',['schedules' => $competition->schedules])

@endsection
