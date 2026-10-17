<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditParameterResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_parameter_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('audit_id')->unsigned();
            $table->bigInteger('qm_sheet_id')->unsigned();
            $table->bigInteger('parameter_id')->unsigned();
            $table->float('orignal_weight', 8, 2)->unsigned()->default(0);
            $table->float('temp_weight', 8, 2)->unsigned()->default(0);
            $table->float('with_fatal_score', 8, 2)->unsigned()->default(0);
            $table->float('without_fatal_score', 8, 2)->unsigned()->default(0);
            $table->float('with_fatal_score_per', 8, 2)->unsigned()->default(0);
            $table->float('without_fatal_score_pre', 8, 2)->unsigned()->default(0);
            $table->tinyInteger('is_critical')->default(0);
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
        Schema::dropIfExists('audit_parameter_results');
    }
}
