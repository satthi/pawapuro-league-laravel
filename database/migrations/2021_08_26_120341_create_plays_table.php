<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('game_id');
            $table->integer('team_id');
            $table->integer('inning');
            $table->integer('type'); // 1: stamen/2:member_charnge/3:dageki_kekka/4:tourui/5: point only
            $table->integer('result_id')->nullable();
            $table->integer('out_count')->nullable();
            $table->integer('point_count')->nullable();
            $table->integer('player_id')->nullable();
            $table->integer('pitcher_id')->nullable();
            $table->integer('dajun')->nullable(); // stamen/member_change
            $table->integer('position')->nullable(); // stamen/member_change
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
        Schema::dropIfExists('plays');
    }
}
