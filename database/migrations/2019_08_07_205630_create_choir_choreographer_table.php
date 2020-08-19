<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoirChoreographerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choir_choreographer', function (Blueprint $table) {

            $table->foreignId('choir_id')
                ->constrained('choirs')
                ->onDelete('cascade');

            $table->foreignId('choreographer_id')
                ->constrained('people')
                ->onDelete('cascade');

            $table->primary(['choir_id', 'choreographer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choir_choreographer');
    }
}
