<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxScoreToCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->integer('max_score')->unsigned()->default(10)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('criteria', function (Blueprint $table) {
            $table->dropColumn('max_score');
        });
    }
}
