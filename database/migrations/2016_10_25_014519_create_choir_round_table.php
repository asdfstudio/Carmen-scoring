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

        $table->foreignId('choir_id')
          ->constrained('choirs')
          ->onDelete('cascade');

        $table->foreignId('round_id')
          ->constrained('rounds')
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
