<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveRoundsUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // We're adding foreign keys so turn this off first;
        Schema::disableForeignKeyConstraints();

        // Add Competition ID to rounds
        Schema::table('rounds', function (Blueprint $table) {
            $table->foreignId('competition_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('caption_weighting_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('scoring_method_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('sheet_id')
                ->constrained()
                ->onDelete('cascade');
        });

        // Move the competition id and the round id to the division
        Schema::table('divisions', function (Blueprint $table) {
            // $table->dropIndex('competition_id');
            $table->foreignId('round_id')
                ->constrained()
                ->onDelete('cascade');
            $table->integer('max_choirs');
        });

        $update = 'UPDATE rounds r JOIN divisions d ON r.division_id = d.id SET r.competition_id = d.competition_id, '.
            'r.caption_weighting_id = d.caption_weighting_id, r.scoring_method_id = d.scoring_method_id, r.sheet_id = d.sheet_id';
        DB::update($update);

        $update = 'UPDATE divisions d JOIN rounds r ON r.division_id = d.id SET d.round_id = r.id, d.max_choirs = r.max_choirs';
        DB::update($update);

        // Drop the columns with the transferred data
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropColumn('competition_id');
            $table->dropColumn('caption_weighting_id');
        });

        Schema::table('rounds', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
            $table->dropColumn('max_choirs');

        });

        // Turn foreign keys back on
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We're adding foreign keys so turn this off first;
        Schema::disableForeignKeyConstraints();

        // Add competition id back to division
        Schema::table('divisions', function(Blueprint $table) {
            $table->integer('competition_id')->index();
            $table->integer('caption_weighting_id')->index();
            $table->integer('scoring_method_id')->index();
            $table->integer('sheet_id')->index();
        });

        Schema::table('rounds', function(Blueprint $table) {
            $table->foreignId('division_id')
              ->constrained('divisions')
              ->onDelete('cascade');
            $table->integer('max_choirs');

        });

        // copy the round's competition id back to the division
        $update = 'UPDATE divisions d JOIN rounds r on d.round_id = r.id SET d.competition_id = r.competition_id, '.
            'd.caption_weighting_id = r.caption_weighting_id, d.scoring_method_id = r.scoring_method_id, d.sheet_id = r.sheet_id';
        DB::update($update);

        $update = 'UPDATE rounds r JOIN divisions d on d.round_id = r.id SET r.division_id = d.id, r.max_choirs = d.max_choirs';
        DB::update($update);


        // // Drop the new round's competition id
        Schema::table('rounds', function (Blueprint $table) {
            $table->dropForeign(['competition_id']);
            $table->dropColumn('competition_id');
            $table->dropForeign(['caption_weighting_id']);
            $table->dropColumn('caption_weighting_id');
            $table->dropForeign(['scoring_method_id']);
            $table->dropColumn('scoring_method_id');
            $table->dropForeign(['sheet_id']);
            $table->dropColumn('sheet_id');
        });

        Schema::table('divisions', function(Blueprint $table) {
            $table->dropForeign(['round_id']);
            $table->dropColumn('round_id');
            $table->dropColumn('max_choirs');
        });

        Schema::enableForeignKeyConstraints();
    }
}
