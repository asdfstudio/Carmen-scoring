<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('schedule_id')->index();
            $table->integer('round_id')->index();
						$table->integer('choir_id')->index();
            $table->integer('performance_order');
            $table->time('scheduled_time')->nullable();
            $table->timestamps();
            //$table->softDeletes();

            //$table->unique(['schedule_id', 'round_id', 'choir_id']);

            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->foreign('round_id')->references('id')->on('rounds')->onDelete('cascade');
            $table->foreign('choir_id')->references('id')->on('choirs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('schedule_items');
    }
}
