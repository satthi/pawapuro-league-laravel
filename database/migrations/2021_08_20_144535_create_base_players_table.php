<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('base_team_id');
            $table->integer('number');
            $table->string('name', 100);
            $table->string('name_short', 100);
            $table->integer('hand_p');
            $table->integer('hand_b');
            $table->integer('position_main');
            $table->integer('position_sub1');
            $table->integer('position_sub2');
            $table->integer('position_sub3');
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
        Schema::dropIfExists('base_players');
    }
}
