<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('app_id');
            $table->string('driver', 255);
            $table->string('host', 255)->nullable();
            $table->integer('port')->nullable();
            $table->string('encryption', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('mail_type', 255);
            $table->string('subject_prefix', 255)->default('');
            $table->timestamps();

            $table->foreign('app_id')->references('id')->on('apps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_settings');
    }
}
