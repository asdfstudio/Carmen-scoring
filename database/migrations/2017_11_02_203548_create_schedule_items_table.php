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
            $table->id();
            $table->integer('performance_order');
            $table->time('scheduled_time')->nullable();
            $table->timestamps();
            //$table->softDeletes();

            //$table->unique(['schedule_id', 'round_id', 'choir_id']);

            $table->foreignId('schedule_id')
              ->constrained('schedules')
              ->onDelete('cascade');
            $table->foreignId('round_id')
              ->constrained('rounds')
              ->onDelete('cascade');
            $table->foreignId('choir_id')
              ->constrained('choirs')
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
        Schema::drop('schedule_items');
    }
}
