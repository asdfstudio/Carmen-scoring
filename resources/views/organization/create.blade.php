@extends('layouts.app')

@section('content')
		
    <h1>Create an Organization, Form Builder Version</h1>
    
		{!! form($form) !!}
    
    <hr />

		<h1>Create an Organization, Laravel Collective Version</h1>

		{{ Form::model(NULL, array('route' => array('admin.organization.store'))) }}
		
    @include('organization.form')
    
    {{ Form::submit('Create Organization', array('class' => 'btn btn-primary')) }}
    
		{{ Form::close() }}
    
@endsection