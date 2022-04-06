<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlayersTableKantoKanpu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->integer('p_kanto')->default(0)->nullable();
            $table->integer('p_kanpu')->default(0)->nullable();
        });
        Schema::table('base_players', function (Blueprint $table) {
            $table->integer('p_kanto')->default(0)->nullable();
            $table->integer('p_kanpu')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
           $table->dropColumn('p_kanto');  //カラムの削除
           $table->dropColumn('p_kanpu');  //カラムの削除
        });
        Schema::table('base_players', function (Blueprint $table) {
           $table->dropColumn('p_kanto');  //カラムの削除
           $table->dropColumn('p_kanpu');  //カラムの削除
        });
    }
}
