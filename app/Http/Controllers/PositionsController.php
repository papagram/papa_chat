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

            foreach ($inputs['hex_numbers'] as $index => $hexNumber) {
                Position::create([
                    'fleet_id' => $inputs['fleet_ids'][$index],
                    'hex_number' => $hexNumber,
                    'turn_number' => $turn->number,
                ]);
            }

            $status = $turn->status;
            if ($turn->status === 1) {
                $status = 2;
            } elseif ($turn->status === 2) {
                $status = 3;
            }

            $turn->fill(['game_id' => $inputs['game_id'], 'current_player_id' => $otherPlayer->id, 'number' => $turn->number, 'status' => $status]);
            $turn->save();
            DB::commit();
        } catch (Throwable $e) {
            report($e);
            DB::rollBack();
            dd($e->getMessage());
        }

        broadcast(app(GameInformationChanged::class, compact('turn')));

        $positions = Position::whereIn('fleet_id', $inputs['fleet_ids'])->where('turn_number', $turn->number)->get();
        if ($status === 3) {
            $otherPlayer->load(['fleets']);
            $otherPositions = Position::whereIn('fleet_id', $otherPlayer->fleets->pluck('id'))->where('turn_number', $turn->number)->get();
            $ret = app(ZocService::class, compact('positions', 'otherPositions'))->handle();
        }

        broadcast(app(PositionsSent::class, compact('turn')));

        if ($status === 3) {
            broadcast(app(ZocResponse::class, compact('ret')));

            $turn->fill(['game_id' => $inputs['game_id'], 'current_player_id' => $inputs['player_id'], 'number' => $turn->number + 1, 'status' => 1]);
            $turn->save();
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
