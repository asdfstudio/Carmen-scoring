<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionAwardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_award_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('division_id')->unsigned()->index();
            $table->integer('caption_id')->unsigned()->default(0);
            $table->integer('award_count')->unsigned()->default(0);
            $table->text('award_sponsors')->nullable();
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
        Schema::drop('division_award_settings');
    }
}
