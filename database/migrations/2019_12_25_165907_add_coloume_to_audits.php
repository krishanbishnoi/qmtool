<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColoumeToAudits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->datetime('qc_tat')->nullable();
            $table->datetime('rebuttal_tat')->nullable();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->smallInteger('holiday')->default(1);
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

        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
