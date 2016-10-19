<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionAwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_award', function (Blueprint $table) {
          //$table->increments('id');
          $table->integer('division_id')->unsigned();
          $table->integer('award_id')->unsigned();
          $table->integer('choir_id')->index()->nullable();
          $table->string('recipient')->nullable();

          $table->foreign('division_id')
              ->references('id')
              ->on('divisions')
              ->onDelete('cascade');

              $table->foreign('award_id')
                  ->references('id')
                  ->on('awards')
                  ->onDelete('cascade');

          $table->primary(['division_id', 'award_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('division_award');
    }
}
