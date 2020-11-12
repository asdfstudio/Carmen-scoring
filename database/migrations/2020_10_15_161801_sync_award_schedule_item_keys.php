<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SyncAwardScheduleItemKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_items', function (Blueprint $table) {
            $table->dropForeign(['choir_id']);
            $table->dropForeign(['division_id']);

            $table->unsignedBigInteger('choir_id')->after('id')->default(0)->change();
            $table->unsignedBigInteger('division_id')->after('choir_id')->default(0)->change();
        });

        Schema::table('award_schedule_items', function (Blueprint $table) {
            $table->dropForeign(['award_id']);

            $table->unsignedBigInteger('round_id')->after('division_id')->default(0)->change();
            $table->unsignedBigInteger('caption_id')->after('round_id')->default(0)->change();
            $table->unsignedBigInteger('award_id')->after('caption_id')->default(0)->change();
            $table->integer('rank')->after('award_id')->default(0)->change();
            $table->unsignedInteger('rank')->after('caption_id')->default(0)->change();
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
            $table->foreignId('award_id')
                ->change()
                ->constrained('awards')
                ->onDelete('cascade');

            $table->integer('caption_id')->after('award_id')->change();
            $table->integer('round_id')->unsigned()->after('division_id')->default(0)->change();
            $table->integer('rank')->after('award_id')->change();
        });

        Schema::table('schedule_items', function (Blueprint $table) {
            $table->foreignId('choir_id')
                ->change()
                ->constrained('choirs')
                ->onDelete('cascade');

            $table->foreignId('division_id')
                ->change()
                ->constrained('divisions')
                ->onDelete('cascade');
        });
    }
}
