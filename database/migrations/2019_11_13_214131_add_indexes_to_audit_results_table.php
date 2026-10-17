<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToAuditResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_results', function (Blueprint $table) {
            $table->foreign('audit_id')->references('id')->on('audits');
            $table->foreign('parameter_id')->references('id')->on('qm_sheet_parameters');
            $table->foreign('sub_parameter_id')->references('id')->on('qm_sheet_sub_parameters');
            
            $table->index(['reason_id','reason_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_results', function (Blueprint $table) {
            //
        });
    }
}
