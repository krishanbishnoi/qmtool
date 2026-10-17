<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('partner_id')->unsigned();
            $table->bigInteger('qm_sheet_id')->unsigned();
            $table->bigInteger('process_id')->unsigned();
            $table->bigInteger('raw_data_id')->unsigned();
            // $table->bigInteger('qtl_id')->unsigned();
            // $table->bigInteger('qa_id')->unsigned();
            $table->bigInteger('audited_by_id')->unsigned();
            $table->tinyInteger('is_critical')->default(0);
            $table->float('overall_score', 8, 2)->unsigned()->default(0);
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
        Schema::dropIfExists('audits');
    }
}
