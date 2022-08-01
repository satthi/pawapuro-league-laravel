<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSeasonsTableMvpEtc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->integer('mvp_player_id')->index()->nullable();
            $table->integer('b9_1_player_id')->index()->nullable();
            $table->integer('b9_2_player_id')->index()->nullable();
            $table->integer('b9_3_player_id')->index()->nullable();
            $table->integer('b9_4_player_id')->index()->nullable();
            $table->integer('b9_5_player_id')->index()->nullable();
            $table->integer('b9_6_player_id')->index()->nullable();
            $table->integer('b9_7_player_id')->index()->nullable();
            $table->integer('b9_8_player_id')->index()->nullable();
            $table->integer('b9_9_player_id')->index()->nullable();
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
           $table->dropColumn('mvp_player_id');  //カラムの削除
           $table->dropColumn('b9_1_player_id');  //カラムの削除
           $table->dropColumn('b9_2_player_id');  //カラムの削除
           $table->dropColumn('b9_3_player_id');  //カラムの削除
           $table->dropColumn('b9_4_player_id');  //カラムの削除
           $table->dropColumn('b9_5_player_id');  //カラムの削除
           $table->dropColumn('b9_6_player_id');  //カラムの削除
           $table->dropColumn('b9_7_player_id');  //カラムの削除
           $table->dropColumn('b9_8_player_id');  //カラムの削除
           $table->dropColumn('b9_9_player_id');  //カラムの削除
        });
    }
}
