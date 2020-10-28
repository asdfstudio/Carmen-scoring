<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionAwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_award', function (Blueprint $table) {
          $table->string('recipient')->nullable();
          $table->foreignId('choir_id')
            ->nullable()
            ->constrained('choirs')
            ->index();

          $table->foreignId('division_id')
            ->constrained('divisions')
            ->onDelete('cascade');

          $table->foreignId('award_id')
            ->constrained('awards')
            ->onDelete('cascade');

          $table->primary(['division_id', 'award_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('division_award');
    }
}
