<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoirDirectorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choir_director', function (Blueprint $table) {

            $table->foreignId('choir_id')
                ->constrained('choirs')
                ->onDelete('cascade');

            $table->foreignId('director_id')
                ->constrained('people')
                ->onDelete('cascade');

            $table->primary(['choir_id', 'director_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choir_director');
    }
}
