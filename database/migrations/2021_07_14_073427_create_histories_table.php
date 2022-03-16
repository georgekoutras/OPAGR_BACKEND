<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{

    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {

            $table->timestamp('recorded_at',0);
            $table->foreignId('device_id')->constrained();
            /* --------------------------------------------------- */
            $table->bigIncrements('history_id');
            /* --------------------------------------------------- */
            $table->foreignId('debugging_id')->constrained();
            $table->boolean('activation_index');
            $table->enum('channel',['gprs','iridium','web']);
            $table->boolean('is_last');
            /* --------------------- PAYLOAD --------------------- */
            $table->unsignedTinyInteger('version');
            $table->unsignedTinyInteger('panic');
            $table->float('battery');
            $table->float('vcc_voltage');
            $table->decimal('latitude',11,7);
            $table->decimal('longitude',11,7);
            $table->integer('hdop')->nullable();
            $table->unsignedInteger('gps_failure')->nullable();
            $table->unsignedInteger('iridium_failure')->nullable();
            $table->string('relay_state',8);
            $table->tinyInteger('refresh_index');
            $table->float('air_temperature')->nullable();
            $table->unsignedFloat('air_humidity')->nullable();
            $table->unsignedFloat('noise')->nullable();
            $table->unsignedInteger('pm10')->nullable();
            $table->unsignedInteger('pm25')->nullable();
            $table->unsignedFloat('atmospheric_pressure')->nullable();
            $table->unsignedInteger('light_intensity')->nullable();
            $table->float('wind_speed')->nullable();
            $table->float('wind_direction')->nullable();
            $table->float('soil_temperature')->nullable();
            $table->float('soil_moisture')->nullable();
            $table->float('soil_ph')->nullable();
            $table->unsignedInteger('soil_ec')->nullable();
            $table->float('rain_accumulation')->nullable();
            $table->float('solar_panel_power')->nullable();
            $table->float('main_battery');
            $table->float('battery_charging_current');
            $table->float('consumption')->nullable();
            $table->string('battery_status',8);
            /* --------------------------------------------------- */
            $table->dropPrimary('histories_history_id_primary');
            $table->primary(['device_id','recorded_at']);
            /* --------------------------------------------------- */
            $table->timestamp('created_at',0);
            $table->softDeletes();
        });

        // Create the hyperTable
        DB::select("SELECT create_hypertable('histories', 'recorded_at', create_default_indexes => FALSE)");
    }

    public function down()
    {
        Schema::table('histories',function (Blueprint $table){
            $table->dropSoftDeletes();
        });
    }
}
