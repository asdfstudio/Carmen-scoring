<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoloDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solo_divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('competition_id')->unsigned()->index();
            $table->integer('sheet_id')->unsigned()->index();
            $table->string('name');
            $table->integer('max_performers');
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_scoring_active')->default(false);
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
        Schema::drop('solo_divisions');
    }
}
