<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalOutputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_outputs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('raw_data_id')->unsigned();
            $table->string('call_id')->nullable();
            $table->bigInteger('client_id')->unsigned();
           
            $table->bigInteger('process_id')->unsigned();
            $table->bigInteger('sample_id')->unsigned();
            $table->string('tl');
            $table->bigInteger('qa_id')->unsigned()->nullable();
            $table->integer('qa_status')->default(0);
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
        Schema::dropIfExists('final_outputs');
    }
}
