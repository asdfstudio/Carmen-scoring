<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TweakCommentUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comment_urls', function (Blueprint $table) {
            $table->integer('recipient_id')->unsigned()->index()->after('competition_id');
            $table->string('recipient_type')->index()->after('competition_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comment_urls', function (Blueprint $table) {
            $table->dropColumn('recipient_id');
            $table->dropColumn('recipient_type');
        });
    }
}
