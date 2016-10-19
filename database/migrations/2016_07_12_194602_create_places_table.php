<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
						$table->string('address')->nullable();
						$table->string('address_2')->nullable();
						$table->string('city')->nullable();
						$table->string('state')->nullable();
						$table->string('postal_code')->nullable();
						$table->integer('subject_id')->index();
						$table->string('subject_type')->index();
						$table->softDeletes();
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
        Schema::drop('places');
    }
}
