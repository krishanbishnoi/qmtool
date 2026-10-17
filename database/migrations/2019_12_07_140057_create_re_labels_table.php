<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('re_labels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('qm_sheet_id')->unsigned();
            $table->string('info_1',257)->nullable();
            $table->string('info_2',257)->nullable();
            $table->string('info_3',257)->nullable();
            $table->string('info_4',257)->nullable();
            $table->string('info_5',257)->nullable();
            $table->string('info_6',257)->nullable();
            $table->string('info_7',257)->nullable();
            $table->string('info_8',257)->nullable();
            $table->string('info_9',257)->nullable();
            $table->string('info_10',257)->nullable();
            $table->string('info_11',257)->nullable();
            $table->string('info_12',257)->nullable();
            $table->string('info_13',257)->nullable();
            $table->string('info_14',257)->nullable();
            $table->string('info_15',257)->nullable();
            $table->string('info_16',257)->nullable();
            $table->string('info_17',257)->nullable();
            $table->string('info_18',257)->nullable();
            $table->string('info_19',257)->nullable();
            $table->string('info_20',257)->nullable();
            $table->string('info_21',257)->nullable();
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
        Schema::dropIfExists('re_labels');
    }
}
