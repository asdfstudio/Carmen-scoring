@extends('layouts.app')

@section('content')

		<h1>Person Details</h1>

		<h2>{{ $person->first_name }} {{ $person->last_name }} - # {{ $person->id }}</h2>
    
    <p>Email: {{ $person->email }}</p>
    
@endsection