<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQcDefectSubParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qc_defect_sub_parameters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('audit_id')->unsigned();
            $table->bigInteger('sub_parameter_id')->unsigned();
            $table->integer('previous_observation')->unsigned();
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
        Schema::dropIfExists('qc_defect_sub_parameters');
    }
}
