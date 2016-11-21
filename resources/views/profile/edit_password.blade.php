@extends('layouts.simple')

@section('content-header')
  @if(Auth::user()->id == $user->id)
    <h1>Update My Password</h1>
  @else
    <h1>Update {{ $user->person->first_name }}'s Password</h1>

    {{ link_to(URL::previous(),'Back to previous page', ['class' => 'action']) }}
  @endif
@endsection

@section('content')


  @if(Auth::user()->id == $user->id)
    <p>
      Need to update your email address or name? {{ link_to_route('profile.edit', 'Update your profile') }}
    </p>
  @endif

  {!! form($form) !!}
@endsection
