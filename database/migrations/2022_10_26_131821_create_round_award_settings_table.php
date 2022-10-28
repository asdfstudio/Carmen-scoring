<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoundAwardSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_award_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('round_id')->unsigned()->index();
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
        Schema::dropIfExists('round_award_settings');
    }
}
