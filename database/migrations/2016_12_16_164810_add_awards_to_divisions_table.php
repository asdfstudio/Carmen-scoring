<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAwardsToDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->integer('music_award_count')->default(0)->after('is_scoring_active');
            $table->integer('show_award_count')->default(0)->after('is_scoring_active');
            $table->integer('combo_award_count')->default(0)->after('is_scoring_active');
            $table->integer('overall_award_count')->default(0)->after('is_scoring_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn('music_award_count');
            $table->dropColumn('show_award_count');
            $table->dropColumn('combo_award_count');
            $table->dropColumn('overall_award_count');
        });
    }
}
