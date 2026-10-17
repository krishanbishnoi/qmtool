<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQmSheetParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qm_sheet_parameters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('qm_sheet_id')->unsigned();
            $table->text('parameter');
            $table->smallInteger('is_non_scoring')->default(0);
            $table->float('weight', 8, 2)->unsigned()->nullable();
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
        Schema::dropIfExists('qm_sheet_parameters');
    }
}
