<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['game_id', 'current_player_id', 'number', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'game_id' => 'integer',
        'current_player_id' => 'integer',
        'number' => 'integer',
        'status' => 'integer',
    ];
}
