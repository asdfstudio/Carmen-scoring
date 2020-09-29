<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsPremiumVoteToAudiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audiences', function (Blueprint $table) {
            $table->integer('is_premium_vote')->after('limit_result')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audiences', function (Blueprint $table) {
            $table->dropColumn('is_premium_vote');
        });
    }
}
