@extends('layouts.simple')

@section('content-header')
  <h1>Update My Password</h1>
@endsection

@section('content')



  <p>
    Need to update your email address or name? {{ link_to_route('profile.edit', 'Update your profile') }}
  </p>

  {!! form($form) !!}
@endsection
