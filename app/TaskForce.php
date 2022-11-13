<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskForce extends Pivot
{
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'task_forces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['country_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany(Player::class)->withTimestamps()->using(PlayerTaskForce::class);
    }
}
