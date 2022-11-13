<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['player_id', 'number'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'player_id' => 'integer',
        'number' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}
