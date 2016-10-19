<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoirDivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choir_division', function (Blueprint $table) {
            //$table->increments('id');
           // $table->timestamps();
					 
					 $table->integer('division_id')->unsigned();
            $table->integer('choir_id')->unsigned();

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onDelete('cascade');

            $table->foreign('choir_id')
                ->references('id')
                ->on('choirs')
                ->onDelete('cascade');

            $table->primary(['division_id', 'choir_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choir_division');
    }
}
