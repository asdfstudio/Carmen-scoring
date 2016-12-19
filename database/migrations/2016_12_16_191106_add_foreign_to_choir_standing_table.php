<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToChoirStandingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('choir_standing', function (Blueprint $table) {
            $table->foreign('standing_id')
                  ->references('id')
                  ->on('standings')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('choir_standing', function (Blueprint $table) {
            $table->dropForeign('choir_standing_standing_id_foreign');
        });
    }
}
