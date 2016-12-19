<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCaptionIdToStandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('standings', function (Blueprint $table) {
            $table->integer('caption_id')->nullable()->after('round_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('standings', function (Blueprint $table) {
            $table->dropColumn('caption_id');
        });
    }
}
