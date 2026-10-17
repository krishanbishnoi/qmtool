<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInAudits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->bigInteger('type_id')->nullable();
            $table->bigInteger('mode_id')->nullable();
            $table->bigInteger('rca1_id')->nullable();
            $table->bigInteger('rca2_id')->nullable();
            $table->bigInteger('rca3_id')->nullable();
            $table->string('rca_other_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            //
        });
    }
}
