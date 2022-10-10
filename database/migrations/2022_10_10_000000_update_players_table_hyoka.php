<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlayersTableHyoka extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->string('hyoka')->nullable(); // ここはＪＳＯＮ
        });
        Schema::table('base_players', function (Blueprint $table) {
            $table->integer('hyoka')->nullable(); // ここはＪＳＯＮから解析して数値
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
            $table->dropColumn('hyoka');
        });
        Schema::table('base_players', function (Blueprint $table) {
            $table->dropColumn('hyoka');
        });
    }
}
