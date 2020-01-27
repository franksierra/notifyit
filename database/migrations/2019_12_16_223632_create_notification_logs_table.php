<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('credential_id');
            $table->enum('type', ['email', 'push', 'sms']);
            $table->uuid('job_id')->index();
            $table->enum('status', ['queued', 'sent', 'failed']);
            $table->longText('payload')->nullable();
            $table->longText('exception')->nullable();
            $table->longText('additional')->nullable();
            $table->timestamps();

            $table->foreign('credential_id')->references('id')->on('credentials');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_logs');
    }
}
