<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamePitchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_pitchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('game_id');
            $table->integer('team_id');
            $table->integer('player_id');
            $table->boolean('win_flag');
            $table->boolean('lose_flag');
            $table->boolean('hold_flag');
            $table->boolean('save_flag');
            $table->integer('jiseki');
            $table->integer('inning');
            $table->integer('daseki');
            $table->integer('dasu');
            $table->integer('hit');
            $table->integer('hr');
            $table->integer('sansin');
            $table->integer('walk');
            $table->integer('dead');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_pitchers');
    }
}
