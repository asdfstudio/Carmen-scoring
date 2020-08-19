<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChoirDivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choir_division', function (Blueprint $table) {
            //$table->id();
           // $table->timestamps();

					 // $table->integer('division_id')->unsigned();
           //  $table->integer('choir_id')->unsigned();

            $table->foreignId('division_id')
                ->constrained('divisions')
                ->onDelete('cascade');

            $table->foreignId('choir_id')
                ->constrained('choirs')
                ->onDelete('cascade');

            $table->primary(['division_id', 'choir_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('choir_division');
    }
}
