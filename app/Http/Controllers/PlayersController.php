<?php

namespace App\Http\Controllers;

use App\Services\ZocService;
use App\Game;
use App\Player;
use App\Position;
use Illuminate\Http\Request;

class PlayersController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game, Player $player)
    {
        $game->load(['gameSetting', 'turn']);
        $player->load([
            'fleets.positions' => function ($query) {
                $query->latest();
            }
        ]);

        $battleInformation = [];
        if ($game->turn->status === 3) {
            // 自軍の艦隊位置を取得
            $positions = Position::whereIn('fleet_id', $player->fleets->pluck('id'))->where('turn_number', $game->turn->number)->get();

            // 敵軍の艦隊位置を取得
            $otherPlayer = Player::with(['fleets'])->where('game_id', $game->id)->where('id', '<>', $player->id)->first();
            $otherPositions = Position::with(['fleet'])->whereIn('fleet_id', $otherPlayer->fleets->pluck('id'))->where('turn_number', $game->turn->number)->get();

            $battleInformation = app(ZocService::class, compact('positions', 'otherPositions'))->handle();
        }

        return view('players.show', compact('game', 'player', 'battleInformation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(Player $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $player)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        //
    }
}
