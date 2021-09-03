<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('base_player_id')->index();
            $table->integer('team_id')->index();
            $table->string('number');
            $table->string('name', 100);
            $table->string('name_short', 100);
            $table->integer('hand_p');
            $table->integer('hand_b');
            $table->integer('position_main');
            $table->integer('position_sub1')->nullable();
            $table->integer('position_sub2')->nullable();
            $table->integer('position_sub3')->nullable();
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
        Schema::dropIfExists('players');
    }
}
