@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <positions-inputs :game='@json($game)' :player='@json($player)'/>
        </div>
    </div>
</div>
@stop
