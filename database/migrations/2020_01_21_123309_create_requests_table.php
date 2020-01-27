<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('credential_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->string('method', 15);
            $table->string('uri', 255)->index();
            $table->ipAddress('ip');
            $table->longText('headers');
            $table->longText('params');
            $table->smallInteger('status_code')->nullable();
            $table->longText('response')->nullable();
            $table->float('exec_time')->nullable();
            $table->timestamps();

            $table->foreign('credential_id')->references('id')->on('credentials');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
