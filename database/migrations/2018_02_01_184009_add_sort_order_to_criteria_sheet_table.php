<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSortOrderToCriteriaSheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('criterion_sheet', function (Blueprint $table) {
            $table->integer('sequence')->default(0)->unsigned()->after('sheet_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('criterion_sheet', function (Blueprint $table) {
            $table->dropColumn('sequence');
        });
    }
}
