<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveGenderFromSoloPerformerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('performers', function (Blueprint $table) {
            $table->renameColumn('gender', 'category');
            $table->renameColumn('gender_place', 'category_place');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performers', function (Blueprint $table) {
          $table->renameColumn('category', 'gender');
          $table->renameColumn('category_place', 'gender_place');
        });
    }
}
