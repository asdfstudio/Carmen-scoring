<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoirStandingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choir_standing', function (Blueprint $table) {
            $table->id();
            $table->integer('standing_id')->unsigned()->index();
            $table->integer('choir_id')->index();
            $table->integer('raw_rank');
            $table->integer('final_rank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choir_standing');
    }
}
