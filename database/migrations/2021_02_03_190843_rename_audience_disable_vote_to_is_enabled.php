<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAudienceDisableVoteToIsEnabled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vote_settings', function (Blueprint $table) {
            $table->renameColumn('disable_vote', 'is_enabled');
        });

        // Flip the boolean value
        $update = 'UPDATE vote_settings set is_enabled = 1 - is_enabled';
        DB::update($update);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Flip the boolean value
        $update = 'UPDATE vote_settings set is_enabled = 1 - is_enabled';
        DB::update($update);

        Schema::table('vote_settings', function (Blueprint $table) {
            $table->renameColumn('is_enabled', 'disable_vote');
        });
    }
}
