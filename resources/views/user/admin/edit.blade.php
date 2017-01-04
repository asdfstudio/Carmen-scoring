@extends('layouts.simple')

@section('breadcrumbs')

@endsection

@section('content-header')
	<h1>Edit user</h1>

	{{ link_to_route('admin.user.index', 'Back to users', [], ['class' => 'action']) }}
@endsection

@section('content')


		{!! form($form) !!}


		@if($user->person->is_judge == false)
			<hr>

			<h3>Turn this user into a judge?</h3>
			{!! form($makeJudgeForm) !!}
		@endif



@endsection
