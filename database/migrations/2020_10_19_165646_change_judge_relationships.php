<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeJudgeRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('division_judge', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropForeign(['caption_id']);
            $table->dropForeign(['judge_id']);
            $table->dropPrimary();
            $table->unsignedBigInteger('round_id')->after('division_id');
        });

        Schema::rename('division_judge', 'round_judge');

        $update = 'UPDATE round_judge rj join divisions d on rj.division_id = d.id SET rj.round_id = d.round_id';
        DB::update($update);

        Schema::table('round_judge', function(Blueprint $table) {
            $table->foreign('round_id')->references('id')->on('rounds');
            $table->foreign('caption_id')->references('id')->on('captions');
            $table->foreign('judge_id')->references('id')->on('people');
            $table->primary(['round_id', 'judge_id', 'caption_id']);
            $table->dropColumn('division_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('round_judge', function(Blueprint $table) {
            $table->dropForeign(['round_id']);
            $table->dropForeign(['caption_id']);
            $table->dropForeign(['judge_id']);
            $table->dropPrimary();
            $table->unsignedBigInteger('division_id')->after('round_id');
        });

        $update = 'UPDATE round_judge rj join divisions d on rj.round_id = d.round_id SET rj.division_id = d.id';
        DB::update($update);

        Schema::rename('round_judge', 'division_judge');

        Schema::table('division_judge', function(Blueprint $table) {
            $table->foreign('division_id')->references('id')->on('divisions');
            $table->foreign('caption_id')->references('id')->on('captions');
            $table->foreign('judge_id')->references('id')->on('people');
            $table->primary(['division_id', 'judge_id', 'caption_id']);
            $table->dropColumn('round_id');
        });
    }
}
