@extends('layouts.simple')

@section('breadcrumbs')
	{!! Breadcrumbs::render('organizer.user.edit', $user) !!}
@endsection

@section('content-header')
	<h1>Edit user</h1>

	{{ link_to_route('organizer.user.index', 'Back to users', [], ['class' => 'action']) }}
@endsection

@section('content')


		{!! form($form) !!}

@endsection

@section('body-footer')
  <script>let getNewUsernameURL = '{{ route('organizer.user.username.new') }}'</script>
  <script src="/js/user-person-form.js"></script>
@endsection