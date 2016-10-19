<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_scores', function (Blueprint $table) {
						$table->increments('id');
						$table->integer('division_id')->unsigned()->index();
						$table->integer('round_id')->index();
						$table->integer('choir_id')->unsigned()->index();
						$table->integer('judge_id')->unsigned()->index();
						$table->integer('criterion_id')->unsigned();
						$table->decimal('score', 5, 1);
						$table->softDeletes();
						$table->timestamps();
            
						$table->foreign('division_id')
                ->references('id')
                ->on('division')
                ->onDelete('cascade');
						
            $table->foreign('choir_id')
                ->references('id')
                ->on('choir')
                ->onDelete('cascade');
								
						$table->foreign('judge_id')
                ->references('id')
                ->on('peopl')
                ->onDelete('cascade');
								
						$table->foreign('criterion_id')
                ->references('id')
                ->on('criteria')
                ->onDelete('cascade');


            //$table->primary(['division_id','round','choir_id','judge_id','criterion_id'], 'div_rd_cho_jud_cri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('raw_scores');
    }
}
