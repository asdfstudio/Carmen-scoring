<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCriteriaSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criterion_sheet', function (Blueprint $table) {
            $table->foreignId('criterion_id')
                ->constrained('criteria')
                ->onDelete('cascade');

            $table->foreignId('sheet_id')
                ->constrained('sheets')
                ->onDelete('cascade');

            $table->primary(['criterion_id', 'sheet_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('criterion_sheet');
    }
}
