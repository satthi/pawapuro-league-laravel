<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStamensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stamens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('game_id')->index();
            $table->integer('team_id')->index();
            $table->integer('dajun');
            $table->integer('position');
            $table->integer('player_id')->index();
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
        Schema::dropIfExists('stamens');
    }
}
