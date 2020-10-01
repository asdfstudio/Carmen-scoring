<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('division_id');
            $table->integer('competition_id');
            $table->text('alias_name');
            $table->tinyInteger('is_dark');
            $table->text('banner_type');
            $table->text('banner_upload');
            $table->text('banner_embed');
            $table->tinyInteger('is_required_login');
            $table->text('social');
            $table->text('list_of_votes');
            $table->tinyInteger('disable_vote');
            $table->integer('limit_result');
            $table->tinyInteger('is_premium_vote');
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
        Schema::dropIfExists('vote_settings');
    }
}
