<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToRawDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('raw_data', function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('process_id')->references('id')->on('processes');
            $table->foreign('qa_id')->references('id')->on('users');
            $table->foreign('partner_location_id')->references('id')->on('regions');
            
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
