<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'task_force_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function taskForces()
    // {
    //     return $this->belongsToMany(TaskForce::class, 'player_task_force', 'player_id', 'task_force_id')->withTimestamps()->using(PlayerTaskForce::class);
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskForce()
    {
        return $this->belongsTo(TaskForce::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fleets()
    {
        return $this->hasMany(Fleet::class);
    }
}
