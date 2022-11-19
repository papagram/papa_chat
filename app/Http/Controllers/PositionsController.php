<?php

namespace App\Http\Controllers;

use App\Events\GameInformationChanged;
use App\Events\PositionsSent;
use App\Events\ZocResponse;
use App\Services\ZocService;
use App\Player;
use App\Position;
use App\Turn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PositionsController extends Controller
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
            $otherPlayer = Player::where('game_id', $inputs['game_id'])->where('id', '<>', $inputs['player_id'])->first();
            $turn = Turn::find($inputs['turn_id']);

            $positions = [];
            foreach ($inputs['hex_numbers'] as $index => $hexNumber) {
                $positions[] = Position::create([
                    'fleet_id' => $inputs['fleet_ids'][$index],
                    'hex_number' => $hexNumber,
                    'turn_number' => $turn->number,
                ]);
            }

            $status = $turn->status;
            $currentPlayerId = $turn->current_player_id;
            if ($turn->status === 1) {
                $status = 2;
                $currentPlayerId = $otherPlayer->id;
            } elseif ($turn->status === 2) {
                $status = 3;
            }

            $turn->fill(['current_player_id' => $currentPlayerId, 'number' => $turn->number, 'status' => $status]);
            $turn->save();
            DB::commit();

            broadcast(app(PositionsSent::class, compact('turn', 'positions') + ['playerId' => $inputs['player_id']]));
            broadcast(app(GameInformationChanged::class, compact('turn')));
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
        }

        if ($status === 3) {
            $positions = Position::whereIn('fleet_id', $inputs['fleet_ids'])->where('turn_number', $turn->number)->get();
            $otherPlayer->load(['fleets']);
            $otherPositions = Position::whereIn('fleet_id', $otherPlayer->fleets->pluck('id'))->where('turn_number', $turn->number)->get();
            $battleInformation = app(ZocService::class, compact('positions', 'otherPositions'))->handle();
            broadcast(app(ZocResponse::class, compact('battleInformation')));
        }

        return compact('turn');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
