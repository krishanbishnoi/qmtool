<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInFailedMonthTargetRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('failed_month_target_rows', function (Blueprint $table) {
            $table->string('zone')->nulable();
            $table->string('location')->nulable();
            $table->string('brand')->nulable();
            $table->string('circle')->nulable();
            $table->string('week_1_target')->nulable();
            $table->string('week_2_target')->nulable();
            $table->string('week_3_target')->nulable();
            $table->string('week_4_target')->nulable();
            $table->integer('slot_batch_id')->nulable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('failed_month_target_rows', function (Blueprint $table) {
            //
        });
    }
}
