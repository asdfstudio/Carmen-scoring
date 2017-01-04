<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSponsorsToDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->text('music_award_sponsors')->nullable()->after('music_award_count');
            $table->text('show_award_sponsors')->nullable()->after('music_award_count');
            $table->text('combo_award_sponsors')->nullable()->after('music_award_count');
            $table->text('overall_award_sponsors')->nullable()->after('music_award_count');
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
            $table->dropColumn('music_award_sponsors');
            $table->dropColumn('show_award_sponsors');
            $table->dropColumn('combo_award_sponsors');
            $table->dropColumn('overall_award_sponsors');
        });
    }
}
