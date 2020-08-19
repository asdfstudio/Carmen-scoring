<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionPenaltyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_penalty', function (Blueprint $table) {

          $table->foreignId('division_id')
            ->constrained('divisions')
            ->onDelete('cascade');

          $table->foreignId('penalty_id')
            ->constrained('penalties')
            ->onDelete('cascade');

          $table->primary(['division_id', 'penalty_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('division_penalty');
    }
}
