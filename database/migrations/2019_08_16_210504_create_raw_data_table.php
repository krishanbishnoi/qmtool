<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('status')->default(0);
            $table->date('dump_date');
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('partner_id')->unsigned();
            $table->bigInteger('process_id')->unsigned();
            $table->bigInteger('qa_id')->unsigned()->nullable();
            $table->bigInteger('qtl_id')->unsigned()->nullable();
            $table->string('call_id')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('emp_id')->nullable();
            $table->string('lob')->nullable();
            $table->string('tl')->nullable();
            $table->string('doj')->nullable();
            $table->string('location')->nullable();
            $table->string('language')->nullable();
            $table->string('call_time')->nullable();
            $table->string('call_duration')->nullable();
            $table->string('call_type')->nullable();
            $table->string('call_sub_type')->nullable();
            $table->string('disposition')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('hangup_details')->nullable();
            $table->string('customer_name')->nullable();
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
        Schema::dropIfExists('raw_data');
    }
}
