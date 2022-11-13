<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('current_player_id')->comment('現在ターンのプレイヤーID');
            $table->unsignedTinyInteger('number')->default(1)->comment('何ターン目');
            $table->unsignedTinyInteger('status')->default(1)->comment('1: 先攻入力待ち, 2: 後攻入力待ち, 3: ZOC判定');
            $table->timestamps();

            $table->foreign('game_id')->references('id')->on('games')->onUpdate('cascade')->onDelete(('cascade'));
            $table->foreign('current_player_id')->references('id')->on('players')->onUpdate('cascade')->onDelete(('cascade'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turns');
    }
}
