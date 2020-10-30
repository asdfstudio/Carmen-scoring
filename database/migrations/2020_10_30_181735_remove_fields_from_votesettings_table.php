<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsFromVotesettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vote_settings', function (Blueprint $table) {
            $table->dropColumn(['social','is_required_login']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vote_settings', function (Blueprint $table) {
            $table->smallInteger('is_required_login')->after('banner_embed')->default(1);
            $table->text('social')->after('banner_embed')->default(null);
        });
    }
}
