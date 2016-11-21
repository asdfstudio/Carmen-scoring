<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoirRoundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('choir_round', function (Blueprint $table) {
        $table->integer('choir_id')->index()->unsigned();
        $table->integer('round_id')->index()->nullable();

        $table->foreign('choir_id')
            ->references('id')
            ->on('choirs')
            ->onDelete('cascade');

            $table->foreign('round_id')
                ->references('id')
                ->on('rounds')
                ->onDelete('cascade');

        $table->primary(['choir_id', 'round_id']);
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choir_round');
    }
}
