@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-9">
                    <positions-inputs :game='@json($game)' :player='@json($player)' :battle-information='@json($battleInformation)'/>
                </div>
                <div class="col-md-3">
                    <p>情報</p>
                    <game-information :game='@json($game)' :player='@json($player)'/>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
