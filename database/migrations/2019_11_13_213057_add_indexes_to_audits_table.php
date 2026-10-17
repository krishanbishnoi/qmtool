<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('qm_sheet_id')->references('id')->on('qm_sheets');
            $table->foreign('process_id')->references('id')->on('processes');
            $table->foreign('raw_data_id')->references('id')->on('raw_data');
            $table->foreign('audited_by_id')->references('id')->on('users');

            
            $table->index(['type_id','mode_id','rca1_id','rca2_id','rca3_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            //
        });
    }
}
