<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQmSheetSubParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qm_sheet_sub_parameters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('qm_sheet_id')->unsigned();
            $table->bigInteger('qm_sheet_parameter_id')->unsigned();
            $table->text('sub_parameter');
            $table->text('details')->nullable();
            $table->float('weight', 8, 2)->unsigned()->default(0);

            $table->tinyInteger('pass')->default(0);
            $table->bigInteger('pass_alert_box_id')->unsigned()->nullable();

            $table->tinyInteger('fail')->default(0);
            $table->bigInteger('fail_alert_box_id')->unsigned()->nullable();

            $table->tinyInteger('critical')->default(0);
            $table->bigInteger('critical_alert_box_id')->unsigned()->nullable();

            $table->tinyInteger('na')->default(0);
            $table->bigInteger('na_alert_box_id')->unsigned()->nullable();

            $table->tinyInteger('pwd')->default(0);
            $table->bigInteger('pwd_alert_box_id')->unsigned()->nullable();
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
        Schema::dropIfExists('qm_sheet_sub_parameters');
    }
}
