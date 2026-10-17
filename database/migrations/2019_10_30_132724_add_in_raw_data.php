<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInRawData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_data', function (Blueprint $table) {
            $table->string('brand_name',257)->nullable();
            $table->string('circle',257)->nullable();
            $table->text('info_1')->nullable();
            $table->text('info_2')->nullable();
            $table->text('info_3')->nullable();
            $table->text('info_4')->nullable();
            $table->text('info_5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_data', function (Blueprint $table) {
            //
        });
    }
}
