<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCallFeedbackLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_call_feedback_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('audit_id');
            $table->integer('mail_trigger_status')->default(0);
            $table->dateTime('mail_trigger_date')->nulable();
            $table->integer('agent_feedback_status')->default(0);
            $table->dateTime('agent_feedback_date')->nulable();
            $table->string('agent_feedback_by_email')->default('');
            $table->string('agent_feedback')->default('');
            $table->string('agent_feedback_recording')->default('');
            $table->integer('agent_want_meeting')->default(0);
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
        Schema::dropIfExists('agent_call_feedback_logs');
    }
}
