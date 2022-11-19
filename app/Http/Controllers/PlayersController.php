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

        $turnNumber = $game->turn->number;
        if ($game->turn->status === 1) {
            $turnNumber = $turnNumber - 1;

            $player->load([
                'fleets.positions' => function ($query) use ($turnNumber) {
                    $query->where('turn_number', $turnNumber);
                },
            ]);

            $otherPlayer = Player::with([
                'fleets.positions' => function ($query) use ($turnNumber) {
                    $query->where('turn_number', $turnNumber);
                },
            ])
            ->where('game_id', $game->id)
            ->where('id', '<>', $player->id)
            ->first();
        } elseif ($game->turn->status === 2) {
            if ($game->turn->current_player_id === $player->id) {
                $prevTurnNumber = $turnNumber - 1;
                $player->load([
                    'fleets.positions' => function ($query) use ($prevTurnNumber) {
                        $query->where('turn_number', $prevTurnNumber);
                    },
                ]);

                $otherPlayer = Player::with([
                    'fleets.positions' => function ($query) use ($turnNumber) {
                        $query->where('turn_number', $turnNumber);
                    },
                ])
                ->where('game_id', $game->id)
                ->where('id', '<>', $player->id)
                ->first();
            } else {
                $player->load([
                    'fleets.positions' => function ($query) use ($turnNumber) {
                        $query->where('turn_number', $turnNumber);
                    },
                ]);

                $prevTurnNumber = $turnNumber - 1;
                $otherPlayer = Player::with([
                    'fleets.positions' => function ($query) use ($turnNumber) {
                        $query->where('turn_number', $turnNumber);
                    },
                ])
                ->where('game_id', $game->id)
                ->where('id', '<>', $player->id)
                ->first();
            }
        } else {
            $player->load([
                'fleets.positions' => function ($query) use ($turnNumber) {
                    $query->where('turn_number', $turnNumber);
                },
            ]);

            $otherPlayer = Player::with([
                'fleets.positions' => function ($query) use ($turnNumber) {
                    $query->where('turn_number', $turnNumber);
                },
            ])
            ->where('game_id', $game->id)
            ->where('id', '<>', $player->id)
            ->first();
        }

        $battleInformation = [];
        if ($game->turn->status === 3) {
            // 自軍の艦隊位置を取得
            $positions = Position::whereIn('fleet_id', $player->fleets->pluck('id'))->where('turn_number', $game->turn->number)->get();

            // 敵軍の艦隊位置を取得
            $otherPositions = Position::with(['fleet'])->whereIn('fleet_id', $otherPlayer->fleets->pluck('id'))->where('turn_number', $game->turn->number)->get();

            $battleInformation = app(ZocService::class, compact('positions', 'otherPositions'))->handle();
        }

        return view('players.show', compact('game', 'player', 'otherPlayer', 'battleInformation'));
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
