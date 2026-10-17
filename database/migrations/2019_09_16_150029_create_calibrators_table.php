<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalibratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calibrators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('calibration_id')->unsigned();
            $table->string('email');
            $table->bigInteger('user_id')->nullable();
            $table->smallInteger('is_master')->default(0);
            $table->smallInteger('status')->default(0);
            $table->float('with_fatal_score', 8, 2)->unsigned()->default(0);
            $table->float('without_fatal_score', 8, 2)->unsigned()->default(0);
            $table->float('difference', 8, 2)->unsigned()->default(0);
            $table->smallInteger('is_critical')->default(0);
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
        Schema::dropIfExists('calibrators');
    }
}
