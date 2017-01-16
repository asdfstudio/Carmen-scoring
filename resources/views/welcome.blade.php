@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome to Carmen Scoring</div>

                <div class="panel-body">
                    {{ link_to('login', 'Log In', ['class' => 'btn btn-primary'])}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
