<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCriteriaSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criterion_sheet', function (Blueprint $table) {
            //$table->increments('id');
            //$table->timestamps();
						
						$table->integer('criterion_id')->unsigned();
            $table->integer('sheet_id')->unsigned();

            $table->foreign('criterion_id')
                ->references('id')
                ->on('criteria')
                ->onDelete('cascade');

            $table->foreign('sheet_id')
                ->references('id')
                ->on('sheets')
                ->onDelete('cascade');

            $table->primary(['criterion_id', 'sheet_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('criterion_sheet');
    }
}
