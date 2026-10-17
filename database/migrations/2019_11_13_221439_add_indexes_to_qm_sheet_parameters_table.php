<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToQmSheetParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qm_sheet_parameters', function (Blueprint $table) {
            $table->foreign('qm_sheet_id')->references('id')->on('qm_sheets');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qm_sheet_parameters', function (Blueprint $table) {
            //
        });
    }
}
