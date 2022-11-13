<?php

namespace App\Http\Controllers;

use App\Fleet;
use App\Game;
use App\PlayerTaskForce;
use Illuminate\Http\Request;

class FleetsController extends Controller
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
    public function create(Game $game)
    {
        $game->load(['players']);
        $ijnPlayer = $game->players->where('task_force_id', 1)->first();
        $usnPlayer = $game->players->where('task_force_id', 2)->first();
        return view('fleets.create', compact('game', 'ijnPlayer', 'usnPlayer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Game $game)
    {
        $inputs = $request->all();
        // dd($inputs);

        foreach ($inputs['ijn_numbers'] as $tfNumber) {
            Fleet::create([
                'player_id' => $inputs['ijn_player_id'],
                'number' => $tfNumber,
            ]);
        }

        foreach ($inputs['usn_numbers'] as $tfNumber) {
            Fleet::create([
                'player_id' => $inputs['usn_player_id'],
                'number' => $tfNumber,
            ]);
        }

        return redirect()->route('games.show', $game);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function show(Fleet $fleet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function edit(Fleet $fleet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fleet $fleet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fleet  $fleet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fleet $fleet)
    {
        //
    }
}
