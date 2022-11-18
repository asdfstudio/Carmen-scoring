<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToChoirDivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('choir_division', function (Blueprint $table) {
            $table->boolean('receives_rankings')->default(true);
            $table->boolean('receives_ratings')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('choir_division', function (Blueprint $table) {
            $table->dropColumn('receives_rankings');
            $table->dropColumn('receives_ratings');
        });
    }
}
