<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRebuttalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rebuttals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('raised_by_user_id')->unsigned();
            $table->bigInteger('raw_data_id')->unsigned();
            $table->bigInteger('audit_id')->unsigned();
            $table->bigInteger('parameter_id')->unsigned();
            $table->bigInteger('sub_parameter_id')->unsigned();
            $table->text('remark')->nullable();
            $table->smallInteger('status')->default(0);
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
        Schema::dropIfExists('rebuttals');
    }
}
