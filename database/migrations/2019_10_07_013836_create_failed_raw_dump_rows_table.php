<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailedRawDumpRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('failed_raw_dump_rows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('failed_raw_dump_slot_id');
            $table->string('call_id');
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
        Schema::dropIfExists('failed_raw_dump_rows');
    }
}
