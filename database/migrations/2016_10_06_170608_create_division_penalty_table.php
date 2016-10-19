<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionPenaltyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_penalty', function (Blueprint $table) {
            //$table->increments('id');
            //$table->timestamps();
            //$table->integer('division_id')->index();
            //$table->integer('penalty_id')->index();

            $table->integer('division_id')->unsigned();
            $table->integer('penalty_id')->unsigned();

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onDelete('cascade');

                $table->foreign('penalty_id')
                    ->references('id')
                    ->on('penalties')
                    ->onDelete('cascade');

            $table->primary(['division_id', 'penalty_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('division_penalty');
    }
}
