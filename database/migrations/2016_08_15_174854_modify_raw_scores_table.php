<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyRawScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('raw_scores', function (Blueprint $table) {
            $table->renameColumn('round','round_id');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('raw_scores', function (Blueprint $table) {
            $table->renameColumn('round_id','round');
        });*/
    }
}
