<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDivisionIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('award_schedule_items', function(Blueprint $table) {
            $table->dropForeign('award_schedule_items_division_id_foreign');
            $table->bigInteger('division_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('award_schedule_items', function(Blueprint $table) {
            $table
              ->constrained('divisions')
              ->onDelete('cascade');
            $table->integer('division_id')->nullable(false)->change();
        });
    }
}
