<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reason_types', function (Blueprint $table) {
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('process_id')->unsigned();
            $table->string('label');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reason_types', function (Blueprint $table) {
            //
        });
    }
}
