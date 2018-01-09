<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerformersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('performers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('solo_division_id')->unsigned()->index();
            $table->integer('choir_id')->unsigned()->index();
            $table->string('name');
            $table->string('gender')->nullable()->index();
            $table->decimal('total_score', 5, 1)->nullable();
            $table->integer('overall_place')->nullable();
            $table->integer('gender_place')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('performers');
    }
}
