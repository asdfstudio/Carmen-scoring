<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisionFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mime')->nullable();
            $table->integer('uploaded_by')->unsigned();
            $table->integer('division_id')->unsigned();
            $table->integer('round_id')->unsigned();
            $table->integer('choir_id')->unsigned();
            $table->string('url');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('division_files');
    }
}
