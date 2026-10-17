<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToAuditParameterResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_parameter_results', function (Blueprint $table) {
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->foreign('qm_sheet_id')->references('id')->on('qm_sheets');
            
            $table->index(['parameter_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_parameter_results', function (Blueprint $table) {
            //
        });
    }
}
