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
            $table->id();
            // $table->integer('award_schedule_id')->unsigned()->index();
            // $table->integer('division_id')->unsigned()->index();
						// $table->integer('award_id')->unsigned()->index();
            $table->integer('performance_order');
            $table->timestamps();
            //$table->softDeletes();

            $table->foreignId('award_schedule_id')
              ->constrained('award_schedules')
              ->onDelete('cascade');
            $table->foreignId('division_id')
              ->constrained('divisions')
              ->onDelete('cascade');
            $table->foreignId('award_id')
              ->constrained('awards')
              ->onDelete('cascade');

            // $table->unique(['schedule_id', 'round_id', 'choir_id']);
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
