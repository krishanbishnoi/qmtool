<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInRebuttals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rebuttals', function (Blueprint $table) {
            $table->bigInteger('re_rebuttal_id')->unsigned()->nullable();
            $table->float('revised_score_with_fatal', 8, 2)->unsigned()->nullable();
            $table->float('revised_score_without_fatal', 8, 2)->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rebuttals', function (Blueprint $table) {
            //
        });
    }
}
