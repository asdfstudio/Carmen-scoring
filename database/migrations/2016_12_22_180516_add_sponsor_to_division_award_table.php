<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSponsorToDivisionAwardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('division_award', function (Blueprint $table) {
            $table->string('sponsor')->nullable()->after('recipient');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('division_award', function (Blueprint $table) {
            $table->dropColumn('sponsor');
        });
    }
}
