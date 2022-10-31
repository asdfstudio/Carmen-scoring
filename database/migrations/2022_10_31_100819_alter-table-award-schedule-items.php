<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAwardScheduleItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('award_schedule_items', function (Blueprint $table) {
            $table->unsignedInteger('awardable_id');
            $table->string('awardable_type');
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
            $table->dropColumn('awardable_id');
            $table->dropColumn('awardable_type');
        });
    }
}
