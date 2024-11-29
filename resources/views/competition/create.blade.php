@extends('layouts.simple')

@section('content')

    <h1>Create a competition</h1>

		{!! form($form) !!}

    
    <script>
        document.getElementById('end_date').addEventListener('input', function (event) {
            const endDate = event.target.value;
            const accessCode = endDate.replace(/-/g, '');
            document.getElementById('access_code').value = accessCode;
        });
    </script>

@endsection
