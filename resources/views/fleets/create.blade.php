@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <p>使用する艦隊にチェックを入れてください</p>
            <form action="{{ route('fleets.store', $game) }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-header">連合艦隊（{{ $ijnPlayer->name }}）</div>
                    <div class="card-body">
                        <input type="hidden" name="ijn_player_id" value="{{ $ijnPlayer->id }}">
                        @foreach (range(1, 13) as $index => $tfNumber)
                            <div class="form-check form-check-inline">
                                <input name="ijn_numbers[]" class="form-check-input" type="checkbox" id="ijn{{ $tfNumber }}" value="{{ $tfNumber }}">
                                <label class="form-check-label" for="ijn{{ $tfNumber }}">TF{{ $tfNumber }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card my-5">
                    <div class="card-header">太平洋艦隊（{{ $usnPlayer->name }}）</div>
                    <div class="card-body">
                        <input type="hidden" name="usn_player_id" value="{{ $usnPlayer->id }}">
                        @foreach (range(1, 13) as $index => $tfNumber)
                            <div class="form-check form-check-inline">
                                <input name="usn_numbers[]" class="form-check-input" type="checkbox" id="usn{{ $tfNumber }}" value="{{ $tfNumber }}">
                                <label class="form-check-label" for="usn{{ $tfNumber }}">TF{{ $tfNumber }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">登録</button>
            </form>
        </div>
    </div>
</div>
@stop
