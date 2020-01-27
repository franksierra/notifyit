<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_devices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('credential_id');
            $table->enum('platform', ['android', 'ios', 'web', 'other']);
            $table->string('uuid')->index();
            $table->string('identity')->nullable();
            $table->string('regid')->nullable();
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
        Schema::dropIfExists('push_devices');
    }
}
