@extends('layouts.simple')

@section('content-header')
  <h1>Update My Profile</h1>
@endsection

@section('content')

  <p>
    Need to update your password? {{ link_to_route('password.edit', 'Update your password') }}
  </p>

  {!! form($form) !!}
@endsection

@section('body-footer')
  <script>let getNewUsernameURL = '{{ route('admin.user.username.new') }}'</script>
  <script src="/js/user-person-form.js"></script>
@endsection