<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveAudienceToRound extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vote_settings', function(Blueprint $table) {
            $table->unsignedInteger('audienceable_id');
            $table->string('audienceable_type');
        });

        $update = 'UPDATE vote_settings set audienceable_id = division_id, audienceable_type = \'App\\\\Division\'';
        DB::update($update);

        Schema::table('vote_settings', function(Blueprint $table) {
            $table->dropColumn('division_id');
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
            $table->integer('division_id');
        });

        $update = 'UPDATE vote_settings set division_id = audienceable_id';
        DB::update($update);

        Schema::table('vote_settings', function(Blueprint $table) {
            $table->dropColumn('audienceable_id');
            $table->dropColumn('audienceable_type');
        });
    }
}
