<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwardScheduleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('award_schedule_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('award_schedule_id')->index();
            $table->integer('division_id')->index();
						$table->integer('award_id')->index();
            $table->integer('performance_order');
            $table->timestamps();
            //$table->softDeletes();

            //$table->unique(['schedule_id', 'round_id', 'choir_id']);

            $table->foreign('award_schedule_id')->references('id')->on('award_schedules')->onDelete('cascade');
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
            $table->foreign('award_id')->references('id')->on('awards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('award_schedule_items');
    }
}
