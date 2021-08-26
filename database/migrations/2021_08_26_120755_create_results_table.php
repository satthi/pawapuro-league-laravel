<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('hit_flag');
            $table->boolean('hit_2_flag');
            $table->boolean('hit_3_flag');
            $table->boolean('hr_flag');
            $table->boolean('sansin_flag');
            $table->boolean('heisatsu_flag');
            $table->boolean('walk_flag');
            $table->boolean('dead_flag');
            $table->boolean('bant_flag');
            $table->boolean('sac_fly_flag');
            $table->integer('out_count');
            $table->boolean('point_require_flag');
            $table->boolean('dasu_count_flag');
            $table->integer('button_position');
            $table->integer('button_type');
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
        Schema::dropIfExists('results');
    }
}
