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

          $table->foreignId('source_round_id')
              ->constrained('rounds')
              ->onDelete('cascade');

          $table->foreignId('target_round_id')
            ->constrained('rounds')
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
