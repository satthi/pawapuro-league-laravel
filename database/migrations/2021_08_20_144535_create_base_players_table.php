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
            $table->integer('base_team_id')->index();
            $table->string('number');
            $table->string('name', 100);
            $table->string('name_short', 100);
            $table->integer('hand_p');
            $table->integer('hand_b');
            $table->integer('position_main');
            $table->integer('position_sub1')->nullable();
            $table->integer('position_sub2')->nullable();
            $table->integer('position_sub3')->nullable();
            // 打者情報
            $table->integer('game')->nullable();
            $table->integer('daseki')->nullable();
            $table->integer('dasu')->nullable();
            $table->integer('hit')->nullable();
            $table->integer('hit_2')->nullable();
            $table->integer('hit_3')->nullable();
            $table->integer('hr')->nullable();
            $table->integer('sansin')->nullable();
            $table->integer('heisatsu')->nullable();
            $table->integer('walk')->nullable();
            $table->integer('dead')->nullable();
            $table->integer('bant')->nullable();
            $table->integer('sac_fly')->nullable();
            $table->integer('daten')->nullable();
            $table->integer('steal_success')->nullable();
            $table->integer('steal_miss')->nullable();
            $table->decimal('avg', 11, 8)->nullable();
            $table->decimal('obp', 11, 8)->nullable();
            $table->decimal('slg', 11, 8)->nullable();
            $table->decimal('ops', 11, 8)->nullable();
            // 投手情報
            $table->integer('p_game')->nullable();
            $table->integer('p_win')->nullable();
            $table->integer('p_lose')->nullable();
            $table->integer('p_hold')->nullable();
            $table->integer('p_save')->nullable();
            $table->integer('p_daseki')->nullable();
            $table->integer('p_dasu')->nullable();
            $table->decimal('p_win_ratio', 11, 8)->nullable();
            $table->integer('p_sansin')->nullable();
            $table->decimal('p_sansin_ratio', 11, 8)->nullable();
            $table->integer('p_hit')->nullable();
            $table->integer('p_hr')->nullable();
            $table->integer('p_walk')->nullable();
            $table->integer('p_dead')->nullable();
            $table->decimal('p_avg', 11, 8)->nullable();
            $table->integer('p_inning')->nullable();
            $table->integer('p_jiseki')->nullable();
            $table->decimal('p_era', 11, 8)->nullable();

            $table->integer('accident_type')->nullable();
            $table->decimal('walk_ritsu', 11, 8)->nullable();
            $table->decimal('p_walk_ritsu', 11, 8)->nullable();

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
