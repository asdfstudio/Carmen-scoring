<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoloRawScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solo_raw_scores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solo_division_id')->unsigned()->index();
            $table->integer('performer_id')->unsigned()->index();
            $table->integer('judge_id')->unsigned()->index();
            $table->integer('criterion_id')->unsigned()->index();
            $table->decimal('score', 5, 1)->nullable();
            $table->softDeletes();
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
        Schema::drop('solo_raw_scores');
    }
}
