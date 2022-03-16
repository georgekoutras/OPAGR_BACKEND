<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCultivationDevicesTable extends Migration
{

    public function up()
    {
        Schema::create('cultivation_devices', function (Blueprint $table) {
            /* --------------------------------------------------- */
            $table->foreignId('cultivation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            /* --------------------------------------------------- */
            $table->primary(['device_id', 'cultivation_id']);
        });
    }

    public function down()
    {
        Schema::drop('cultivation_devices');
    }
}
