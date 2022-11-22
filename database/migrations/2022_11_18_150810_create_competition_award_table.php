<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionAwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_award', function (Blueprint $table) {
            $table->string('recipient')->nullable();
            $table->string('sponsor')->nullable();
            $table->foreignId('choir_id')
                ->nullable()
                ->constrained('choirs')
                ->onDelete('cascade');

            $table->foreignId('competition_id')
                ->constrained('competitions')
                ->onDelete('cascade');

            $table->foreignId('award_id')
                ->constrained('awards')
                ->onDelete('cascade');

            $table->primary(['competition_id', 'award_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['choir_id']);
        $table->dropForeign(['competition_id']);
        $table->dropForeign(['award_id']);
        Schema::dropIfExists('competition_award');
    }
}
