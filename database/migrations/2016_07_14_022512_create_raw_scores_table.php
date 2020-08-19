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
						$table->id();
						$table->decimal('score', 5, 1);
						$table->softDeletes();
						$table->timestamps();

            $table->foreignId('round_id')
              ->constrained('rounds')
              ->onDelete('cascade');

            $table->foreignId('division_id')
              ->constrained('divisions')
              ->onDelete('cascade');

            $table->foreignId('choir_id')
              ->constrained('choirs')
              ->onDelete('cascade');

            $table->foreignId('judge_id')
              ->constrained('people')
              ->onDelete('cascade');

            $table->foreignId('criterion_id')
              ->constrained('criteria')
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
