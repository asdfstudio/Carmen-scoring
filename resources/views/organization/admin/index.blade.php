@extends('layouts.simple')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

@endsection

@section('content-header')
<h1>Organizations</h1>

{{ link_to_route('admin.organization.create', 'Add an organization', [] ,['class' => 'action']) }}
@endsection


@section('content')

@include('organization.admin.list')

@endsection

@section('body-footer')
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script type="text/javascript" src="/dist/js/toggles.js"></script>

@endsection
