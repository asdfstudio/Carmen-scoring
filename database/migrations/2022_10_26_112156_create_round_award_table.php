<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoundAwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_award', function (Blueprint $table) {
          $table->string('recipient')->nullable();
          $table->string('sponsor')->nullable();
          $table->foreignId('choir_id')
            ->nullable()
            ->constrained('choirs')
            ->onDelete('cascade');

          $table->foreignId('round_id')
            ->constrained('rounds')
            ->onDelete('cascade');

          $table->foreignId('award_id')
            ->constrained('awards')
            ->onDelete('cascade');

          $table->primary(['round_id', 'award_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('round_award', function (Blueprint $table) {

            $table->dropForeign(['award_id']);
            $table->dropForeign(['round_id']);
            $table->dropForeign(['choir_id']);
            Schema::drop('round_award');
        });
    }
}
