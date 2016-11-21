<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_connections', function (Blueprint $table) {
          $table->integer('source_round_id')->index()->unsigned();
          $table->integer('target_round_id')->index()->nullable();

          $table->foreign('source_round_id')
              ->references('id')
              ->on('rounds')
              ->onDelete('cascade');

              $table->foreign('target_round_id')
                  ->references('id')
                  ->on('rounds')
                  ->onDelete('cascade');

          $table->primary(['source_round_id', 'target_round_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('round_connections');
    }
}
