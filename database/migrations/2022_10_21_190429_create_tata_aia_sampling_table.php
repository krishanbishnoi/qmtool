<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTataAiaSamplingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tata_aia_sampling', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description');
            $table->string('tl');
            $table->string('process_id');
            $table->bigInteger('client_id')->unsigned();
            $table->string('partner_id');
            $table->string('call_type');
            $table->string('voc_type');
            $table->integer('voc_score');
            $table->integer('random_samples');
            $table->bigInteger('sample_id')->unsigned();
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
        Schema::dropIfExists('tata_aia_sampling');
    }
}
