<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            /*--------------------------------------------------------------*/
            $table->foreignId('cultivation_id')->nullable()->constrained();
            /*--------------------------------------------------------------*/
            $table->enum('state', ['active', 'inactive']);
            $table->boolean('rockblock_module');
            $table->string('imei', 64)->nullable();
            $table->boolean('gprs_module');
            $table->string('gprs_id', 64)->nullable();
            $table->string('iccid', 64)->nullable();
            $table->string('msisdn', 64)->nullable();
            $table->string('imsi', 64)->nullable();
            $table->string('version', 5);
            /*--------------------------------------------------------------*/
            $table->unsignedTinyInteger('refresh_index');
            $table->integer('confirmed_state_id')->nullable();
            $table->integer('pending_state_id')->nullable();
            $table->tinyInteger('refresh_time_loading')->default(0);
            $table->timestamp('last_status_update')->nullable();
            $table->text('relay_names')->nullable();
            $table->string('relay_enabled', 8)->default('00000000');
            $table->string('relay_states', 8)->default('00000000');
            $table->string('relays_loading', 8)->default('00000000');
            $table->timestamp('last_relay_change')->nullable();
            /*--------------------------------------------------------------*/
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
