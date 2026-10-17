<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalibrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calibrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->string('title')->nullabe();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('process_id')->unsigned();
            $table->bigInteger('qm_sheet_id')->unsigned();
            $table->date('due_date');
            $table->smallInteger('status')->default(0);
            $table->bigInteger('created_by')->unsigned();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('calibrations');
    }
}
