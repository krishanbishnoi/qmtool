<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRawDumpCroneFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_dump_crone_filters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->string('filter_client_id');
            $table->string('filter_partner_id');
            $table->string('filter_lob_id');
            $table->string('filter_brand_id');
            $table->string('filter_circle_id');
            $table->string('filter_location_id');
            $table->string('filter_process_id');
            $table->string('filter_sheet_id');
            $table->date('filter_start_date');
            $table->date('filter_end_date');
            $table->integer('file_save_status')->default(0);
            $table->integer('mail_status')->default(0);
            $table->string('file_location')->nullable();
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
        Schema::dropIfExists('raw_dump_crone_filters');
    }
}