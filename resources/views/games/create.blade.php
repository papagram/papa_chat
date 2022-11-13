@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('games.store') }}" method="POST">
                @csrf
                <div class="form-group row">
                    <label for="player1" class="col-sm-2 col-form-label">連合艦隊</label>
                    <div class="col-sm-10">
                      <input type="text" name="players[name][]" class="form-control" id="player1" placeholder="連合艦隊のプレーヤー名">
                      <input type="hidden" name="task_forces[id][]" value="1">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="player2" class="col-sm-2 col-form-label">太平洋艦隊</label>
                    <div class="col-sm-10">
                      <input type="text" name="players[name][]" class="form-control" id="player2" placeholder="太平洋艦隊のプレーヤー名">
                      <input type="hidden" name="task_forces[id][]" value="2">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="player2" class="col-sm-2 col-form-label">先攻</label>
                    <div class="col-sm-10">
                        <select name="task_force_id" class="form-control" id="taskForceList">
                            @foreach ($taskForceList as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">ゲーム開始</button>
            </form>
        </div>
    </div>
</div>
@stop
