<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoloDivisionCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solo_divisions', function (Blueprint $table) {
            $table->string('category_2')->after('max_performers')->nullable();
            $table->string('category_1')->after('max_performers')->nullable();
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
          $table->dropColumn('category_2');
          $table->dropColumn('category_1');
        });
    }
}
