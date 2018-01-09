<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccessCodeToSoloDivisions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solo_divisions', function (Blueprint $table) {
            $table->string('access_code')->after('is_scoring_active')->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solo_divisions', function (Blueprint $table) {
            $table->dropColumn('access_code');
        });
    }
}
