@extends('layouts.simple')

@section('content-header')
  <h3>Divisions</h3>
@endsection

@section('content')

    @include('division.judge.list',['divisions' => $competition->divisions])

@endsection
