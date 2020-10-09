<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSchedulesToDivisionBased extends Migration
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

        Schema::table('schedule_items', function (Blueprint $table) {
            $table->foreignId('division_id')
                ->constrained()
                ->onDelete('cascade');
        });

        $update = 'UPDATE schedule_items si JOIN divisions d on si.round_id = d.round_id SET si.division_id = d.id';
        DB::update($update);

        Schema::table('schedule_items', function (Blueprint $table) {
            $table->dropForeign(['round_id']);
            $table->dropColumn('round_id');
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

        Schema::table('schedule_items', function (Blueprint $table) {
            $table->foreignId('round_id')
                ->constrained()
                ->onDelete('cascade');
        });

        $update = 'UPDATE schedule_items si JOIN divisions d on si.division_id = d.id SET si.round_id = d.round_id';
        DB::update($update);

        Schema::table('schedule_items', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropColumn('division_id');
        });

        // Turn foreign keys back on
        Schema::enableForeignKeyConstraints();
    }
}
