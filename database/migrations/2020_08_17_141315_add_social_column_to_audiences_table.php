<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialColumnToAudiencesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('audiences', function (Blueprint $table) {
      $table->string('social')->after('is_required_login')->nullable();
      $table->string('list_of_votes')->after('social')->nullable();
      $table->smallInteger('disable_vote')->after('list_of_votes')->default(0);
      $table->integer('limit_result')->after('disable_vote')->default(6);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('audiences', function (Blueprint $table) {
      $table->dropColumn('social');
      $table->dropColumn('list_of_votes');
      $table->dropColumn('disable_vote');
      $table->dropColumn('limit_result');
    });
  }
}
