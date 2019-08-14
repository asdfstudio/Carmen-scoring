<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoirDirectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choir_director', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('choir_id')->unsigned();
					  $table->integer('director_id')->unsigned();

            $table->foreign('choir_id')
                ->references('id')
                ->on('choirs')
                ->onDelete('cascade');

            $table->foreign('director_id')
                ->references('id')
                ->on('people')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choir_director');
    }
}
