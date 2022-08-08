<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBasePlayersTitleCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('base_players', function (Blueprint $table) {
            $table->integer('mvp_count_for_fielder')->nullable();
            $table->integer('b9_count_for_fielder')->nullable();
            $table->integer('avg_king_count')->nullable();
            $table->integer('hr_king_count')->nullable();
            $table->integer('daten_king_count')->nullable();
            $table->integer('hit_king_count')->nullable();
            $table->integer('steal_king_count')->nullable();
            $table->integer('kitei_daseki_count')->nullable();
            $table->integer('avg_2wari_8bu_count')->nullable();
            $table->integer('avg_3wari_count')->nullable();
            $table->integer('avg_3wari_2bu_count')->nullable();
            $table->integer('avg_3wari_4bu_count')->nullable();
            $table->integer('hr_10_count')->nullable();
            $table->integer('hr_20_count')->nullable();
            $table->integer('hr_30_count')->nullable();
            $table->integer('hr_40_count')->nullable();
            $table->integer('daten_60_count')->nullable();
            $table->integer('daten_80_count')->nullable();
            $table->integer('daten_100_count')->nullable();
            $table->integer('steal_10_count')->nullable();
            $table->integer('steal_20_count')->nullable();
            $table->integer('steal_30_count')->nullable();
            $table->integer('steal_40_count')->nullable();
            $table->integer('mvp_count_for_pitcher')->nullable();
            $table->integer('b9_count_for_pitcher')->nullable();
            $table->integer('p_era_king_count')->nullable();
            $table->integer('p_win_king_count')->nullable();
            $table->integer('p_win_ratio_king_count')->nullable();
            $table->integer('p_sansin_king_count')->nullable();
            $table->integer('p_hold_king_count')->nullable();
            $table->integer('p_save_king_count')->nullable();
            $table->integer('kitei_tokyu_count')->nullable();
            $table->integer('p_game_50_count')->nullable();
            $table->integer('p_era_1ten_count')->nullable();
            $table->integer('p_era_2ten_count')->nullable();
            $table->integer('p_win_10_count')->nullable();
            $table->integer('p_win_13_count')->nullable();
            $table->integer('p_win_15_count')->nullable();
            $table->integer('p_hold_30_count')->nullable();
            $table->integer('p_save_30_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropColumn('mvp_count_for_fielder');
            $table->dropColumn('b9_count_for_fielder');
            $table->dropColumn('avg_king_count');
            $table->dropColumn('hr_king_count');
            $table->dropColumn('daten_king_count');
            $table->dropColumn('hit_king_count');
            $table->dropColumn('steal_king_count');
            $table->dropColumn('kitei_daseki_count');
            $table->dropColumn('avg_2wari_8bu_count');
            $table->dropColumn('avg_3wari_count');
            $table->dropColumn('avg_3wari_2bu_count');
            $table->dropColumn('avg_3wari_4bu_count');
            $table->dropColumn('hr_10_count');
            $table->dropColumn('hr_20_count');
            $table->dropColumn('hr_30_count');
            $table->dropColumn('hr_40_count');
            $table->dropColumn('daten_60_count');
            $table->dropColumn('daten_80_count');
            $table->dropColumn('daten_100_count');
            $table->dropColumn('steal_10_count');
            $table->dropColumn('steal_20_count');
            $table->dropColumn('steal_30_count');
            $table->dropColumn('steal_40_count');
            $table->dropColumn('mvp_count_for_pitcher');
            $table->dropColumn('b9_count_for_pitcher');
            $table->dropColumn('p_era_king_count');
            $table->dropColumn('p_win_king_count');
            $table->dropColumn('p_win_ratio_king_count');
            $table->dropColumn('p_sansin_king_count');
            $table->dropColumn('p_hold_king_count');
            $table->dropColumn('p_save_king_count');
            $table->dropColumn('kitei_tokyu_count');
            $table->dropColumn('p_game_50_count');
            $table->dropColumn('p_era_1ten_count');
            $table->dropColumn('p_era_2ten_count');
            $table->dropColumn('p_win_10_count');
            $table->dropColumn('p_win_13_count');
            $table->dropColumn('p_win_15_count');
            $table->dropColumn('p_hold_30_count');
            $table->dropColumn('p_save_30_count');
        });
    }
}
