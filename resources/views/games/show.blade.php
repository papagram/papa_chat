@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">連合艦隊（{{ $ijnPlayer->name }}）</div>
                <div class="card-body">
                    <a href="{{ route('players.show', [$game, $ijnPlayer]) }}" class="btn btn-danger">スタート</a>
                </div>
            </div>
            <div class="card my-5">
                <div class="card-header">太平洋艦隊（{{ $usnPlayer->name }}）</div>
                <div class="card-body">
                    <a href="{{ route('players.show', [$game, $usnPlayer]) }}" class="btn btn-danger">スタート</a>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
