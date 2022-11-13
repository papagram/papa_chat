<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['started_at', 'finished_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function gameSetting()
    {
        return $this->hasOne(GameSetting::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function turn()
    {
        return $this->hasOne(Turn::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
