<?php

namespace App\Http\Controllers;

use App\Game;
use App\GameSetting;
use App\Player;
use App\PlayerTaskForce;
use App\TaskForce;
use App\Turn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class GamesContorller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taskForceList = TaskForce::all()->pluck('country_name', 'id');
        return view('games.create', compact('taskForceList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        DB::beginTransaction();

        try {
            $game = Game::create(['started_at' => now()]);
            $players = $game->players()->saveMany([
                app(Player::class)->fill([
                    'name' => $inputs['players']['name'][0],
                    'task_force_id' => $inputs['task_forces']['id'][0],
                ]),
                app(Player::class)->fill([
                    'name' => $inputs['players']['name'][1],
                    'task_force_id' => $inputs['task_forces']['id'][1],
                ]),
            ]);
            foreach ($players as $index => $player) {
                if ((int)$inputs['task_force_id'] === (int)$inputs['task_forces']['id'][$index]) {
                    $game->gameSetting()->save(app(GameSetting::class)->fill(['first_player_id' => $player->id]));
                    $game->turn()->save(app(Turn::class)->fill(['current_player_id' => $player->id]));
                }
            }

            DB::commit();
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
        }

        return redirect()->route('fleets.create', $game);
    }

    /**
     * Display the specified resource.
     *
     * @param  Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        $game->load(['players']);
        $ijnPlayer = $game->players->where('task_force_id', 1)->first();
        $usnPlayer = $game->players->where('task_force_id', 2)->first();
        return view('games.show', compact('game', 'ijnPlayer', 'usnPlayer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
