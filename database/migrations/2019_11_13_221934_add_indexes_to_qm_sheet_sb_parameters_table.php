<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToQmSheetSbParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qm_sheet_sub_parameters', function (Blueprint $table) {
            $table->foreign('qm_sheet_id')->references('id')->on('qm_sheets');
            $table->foreign('qm_sheet_parameter_id')->references('id')->on('qm_sheet_parameters');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qm_sheet_sub_parameters', function (Blueprint $table) {
            //
        });
    }
}
