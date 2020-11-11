<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PerformanceOrderAndTimeUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('choir_round', function(Blueprint $table) {
            $table->unsignedTinyInteger('performance_order');
        });

        Schema::table('schedule_items', function(Blueprint $table) {
            $table->dateTimeTz('scheduled_time')->nullable()->change();
        });

        $update = 'UPDATE choir_round cr join rounds r on cr.round_id = r.id join divisions d on d.round_id = r.id join choir_division cd on cd.division_id = d.id and cr.choir_id = cd.choir_id '.
            'SET cr.performance_order = cd.performance_order';
        DB::update($update);

        // We don't want to save the current date for every old date, set it to the minimum supported date.
        $update = 'UPDATE schedule_items SET scheduled_time = timestamp(\'1000-01-01\', time(scheduled_time))';
        DB::update($update);

        Schema::table('choir_division', function (Blueprint $table) {
            $table->dropColumn('performance_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('choir_division', function(Blueprint $table) {
            $table->integer('performance_order')->unsigned();
        });

        Schema::table('schedule_items', function(Blueprint $table) {
            $table->time('scheduled_time')->nullable()->change();
        });

        $update = 'UPDATE choir_division cd join divisions d on cd.division_id = d.id join choir_round cr on cr.round_id = d.round_id and cr.choir_id = cd.choir_id '.
            'SET cd.performance_order = cr.performance_order;';
        DB::update($update);

        Schema::table('choir_round', function(Blueprint $table) {
            $table->dropColumn('performance_order');
        });
    }
}
