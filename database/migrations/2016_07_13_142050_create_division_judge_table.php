<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionJudgeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_judge', function (Blueprint $table) {
            //$table->increments('id');
            //$table->timestamps();
						
						$table->integer('division_id')->unsigned();
            $table->integer('judge_id')->unsigned();
						$table->integer('caption_id')->unsigned();

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onDelete('cascade');

            $table->foreign('judge_id')
                ->references('id')
                ->on('people')
                ->onDelete('cascade');
						
						$table->foreign('caption_id')
                ->references('id')
                ->on('captions')
                ->onDelete('cascade');

            $table->primary(['division_id', 'judge_id','caption_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('division_judge');
    }
}
