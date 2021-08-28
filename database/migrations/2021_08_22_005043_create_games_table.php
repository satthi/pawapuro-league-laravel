<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('season_id');
            $table->date('date');
            $table->integer('home_team_id');
            $table->integer('visitor_team_id');
            $table->integer('home_probable_pitcher_id')->nullable();
            $table->integer('visitor_probable_pitcher_id')->nullable();
            $table->boolean('dh_flag');
            $table->integer('inning')->nullable();
            $table->integer('out')->nullable();
            $table->integer('home_point')->nullable();
            $table->integer('visitor_point')->nullable();
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
        Schema::dropIfExists('games');
    }
}
