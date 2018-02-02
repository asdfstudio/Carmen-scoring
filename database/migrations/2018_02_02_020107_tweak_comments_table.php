<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TweakCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
          $table->integer('recipient_id')->unsigned()->index()->after('judge_id');
          $table->string('recipient_type')->index()->after('judge_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
          $table->dropColumn('recipient_id');
          $table->dropColumn('recipient_type');
        });
    }
}
