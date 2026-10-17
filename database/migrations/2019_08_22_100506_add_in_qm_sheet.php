<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInQmSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qm_sheet_sub_parameters', function (Blueprint $table) {
            $table->string('fail_reason_types')->nullable();
            $table->string('critical_reason_types')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qm_sheet', function (Blueprint $table) {
            //
        });
    }
}
