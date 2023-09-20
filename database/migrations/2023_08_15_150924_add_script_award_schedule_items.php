<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScriptAwardScheduleItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('award_schedule_items', function (Blueprint $table) {
            $table->addColumn('text','script_award')->nullable();
            $table->string('kind')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('award_schedule_items', function (Blueprint $table) {
            $table->dropColumn('script_award');
            $table->dropColumn('kind');
        });
    }
}
