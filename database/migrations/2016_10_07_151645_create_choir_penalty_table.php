<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoirPenaltyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choir_penalty', function (Blueprint $table) {
            //$table->increments('id');
            //$table->timestamps();
            $table->integer('choir_id')->index();
            $table->integer('penalty_id')->index();
            $table->integer('round_id')->index();
            //$table->integer('division_id')->index();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choir_penalty');
    }
}
