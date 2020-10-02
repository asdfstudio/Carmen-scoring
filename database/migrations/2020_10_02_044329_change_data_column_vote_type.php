<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDataColumnVoteType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('vote_results', function (Blueprint $table) {
        $table->longText('votes')->change();
        $table->longText('premium_votes')->change();
      });

      Schema::table('users', function (Blueprint $table) {
        $table->longText('voted')->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('vote_results', function (Blueprint $table) {
        $table->string('votes')->change();
        $table->string('premium_votes')->change();
      });

      Schema::table('users', function (Blueprint $table) {
        $table->string('voted')->change();
      });
    }
}
