<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCultivationTypesTable extends Migration
{
    public function up()
    {
        Schema::create('cultivation_types', function (Blueprint $table) {
            $table->id();
            $table->string('type',100);
            /* -------------------------------------------------------- */
            $table->float('soil_temp_min')->nullable();
            $table->float('soil_temp_max')->nullable();
            $table->unsignedInteger('soil_moist_min')->nullable();
            $table->unsignedInteger('soil_moist_max')->nullable();
            $table->unsignedInteger('soil_ec_min')->nullable();
            $table->unsignedInteger('soil_ec_max')->nullable();
            $table->float('soil_ph_min')->nullable();
            $table->float('soil_ph_max')->nullable();
            $table->float('air_temp_min')->nullable();
            $table->float('air_temp_max')->nullable();
            $table->unsignedInteger('air_hum_min')->nullable();
            $table->unsignedInteger('air_hum_max')->nullable();
            $table->float('atmospheric_pressure_min')->nullable();
            $table->float('atmospheric_pressure_max')->nullable();
            $table->unsignedInteger('light_intensity_min')->nullable();
            $table->unsignedInteger('light_intensity_max')->nullable();
            $table->float('noise_min')->nullable();
            $table->float('noise_max')->nullable();
            $table->unsignedInteger('pm25_min')->nullable();
            $table->unsignedInteger('pm25_max')->nullable();
            $table->unsignedInteger('pm10_min')->nullable();
            $table->unsignedInteger('pm10_max')->nullable();
            $table->float('wind_speed_min')->nullable();
            $table->float('wind_speed_max')->nullable();
            $table->float('rain_accumulation_min')->nullable();
            $table->float('rain_accumulation_max')->nullable();
            /* -------------------------------------------------------- */
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('cultivation_types', function (Blueprint $table) {
           $table->dropSoftDeletes();
        });
    }
}
