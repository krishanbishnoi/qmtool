<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumeToFailedRawDumpSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('failed_raw_dump_slots', function (Blueprint $table) {
            $table->bigInteger('uploader_id')->nullable();
            $table->smallInteger('visiblity')->default(1);
            $table->string('file_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('failed_raw_dump_slots', function (Blueprint $table) {
            //
        });
    }
}
