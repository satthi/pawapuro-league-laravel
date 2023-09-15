<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePlayersTableCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->integer('card_cost')->nullable();
            $table->json('card_info')->default('{}');
        });
        Schema::table('base_teams', function (Blueprint $table) {
            $table->string('base_color')->nullable();
            $table->string('second_color')->nullable();
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
            $table->dropColumn('card_cost');
            $table->dropColumn('card_info');
        });
        Schema::table('base_teams', function (Blueprint $table) {
            $table->dropColumn('base_color');
            $table->dropColumn('second_color');
        });
    }
}
