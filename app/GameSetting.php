<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_player_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_player_id' => 'integer',
    ];
}
