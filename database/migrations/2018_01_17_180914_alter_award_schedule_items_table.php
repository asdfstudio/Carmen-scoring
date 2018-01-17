<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAwardScheduleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('award_schedule_items', function (Blueprint $table) {
            $table->integer('round_id')->unsigned()->index()->after('division_id')->default(0);
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
            $table->dropColumn('round_id');
        });
    }
}
