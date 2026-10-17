<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInRawDumps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_data', function (Blueprint $table) {
            $table->string('qrc_1')->nullable();
        });
        Schema::table('audits', function (Blueprint $table) {
            $table->string('qrc_2')->nullable();
            $table->string('language_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_dumps', function (Blueprint $table) {
            //
        });
    }
}
