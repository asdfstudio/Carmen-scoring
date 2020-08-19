<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_type', function (Blueprint $table) {

            $table->foreignId('person_id')
                ->constrained('people')
                ->onDelete('cascade');

            $table->foreignId('type_id')
                ->constrained('types')
                ->onDelete('cascade');

            $table->primary(['person_id', 'type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('person_type');
    }
}
