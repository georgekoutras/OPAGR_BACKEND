<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_states', function (Blueprint $table) {
            $table->id();
            /*--------------------------------------------------------------*/
            $table->foreignId('device_id')->constrained();
            /*--------------------------------------------------------------*/
            $table->string('state_text', 100)->nullable();
            $table->integer('refresh_time')->nullable();
            $table->string('relay_states', 24)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->enum('source_sent', ['gsm', 'sat'])->nullable();
            $table->enum('source_confirmed', ['gsm', 'sat'])->nullable();
            /*--------------------------------------------------------------*/
            $table->string('sat_response_id', 32)->nullable();
            $table->tinyInteger('sat_success')->nullable();
            $table->text('sat_error')->nullable();
            $table->text('sat_response')->nullable();
            $table->integer('confirm_after')->nullable();
            /*--------------------------------------------------------------*/
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_states');
    }
}
