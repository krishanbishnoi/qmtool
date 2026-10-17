<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('audit_id')->unsigned();
            $table->bigInteger('parameter_id')->unsigned();
            $table->bigInteger('sub_parameter_id')->unsigned();
            $table->tinyInteger('is_critical')->default(0);
            $table->tinyInteger('is_non_scoring')->default(0);
            $table->bigInteger('selected_option')->unsigned();
            $table->float('score', 8, 2)->unsigned()->default(0);
            $table->text('failure_reason')->nullable();
            $table->text('remark')->nullable();
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
        Schema::dropIfExists('audit_results');
    }
}
