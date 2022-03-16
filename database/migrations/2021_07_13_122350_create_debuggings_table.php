<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebuggingsTable extends Migration
{
    public function up()
    {
        Schema::create('debuggings', function (Blueprint $table) {

            $table->id();
            $table->foreignId('device_id')->constrained();
            /*------------------------- ROCK_BLOCK ------------------------------*/
            $table->string('imei')->nullable();
            $table->integer('momsn')->nullable();
            $table->dateTime('transmit_time')->nullable();
            $table->integer('iridium_cep')->nullable();
            $table->float('iridium_latitude')->nullable();
            $table->float('iridium_longitude')->nullable();
            $table->string('data')->nullable();
            /*---------------------------- GPRS ---------------------------------*/
            $table->string('gprs_id')->nullable();
            $table->string('payload')->nullable();
            /*-------------------------------------------------------------------*/
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('debuggings', function (Blueprint $table){
            $table->dropSoftDeletes();
        });
    }
}
