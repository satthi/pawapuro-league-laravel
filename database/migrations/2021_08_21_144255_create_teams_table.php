<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('base_team_id')->index();
            $table->integer('season_id')->index();
            $table->string('name', 100);
            $table->string('ryaku_name', 100);
            $table->integer('game')->nullable()->index();
            $table->integer('remain')->nullable()->index();
            $table->integer('win')->nullable()->index();
            $table->integer('lose')->nullable()->index();
            $table->integer('draw')->nullable()->index();
            $table->decimal('win_ratio', 11, 8)->nullable();
            $table->integer('hr')->nullable();
            $table->decimal('avg', 11, 8)->nullable();
            $table->decimal('era', 11, 8)->nullable();
            $table->integer('point')->nullable();
            $table->integer('p_point')->nullable();


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
        Schema::dropIfExists('teams');
    }
}
