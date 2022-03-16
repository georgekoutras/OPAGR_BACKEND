<?php

namespace App\Models;

use App\Casts\BatVoltage;
use App\Casts\VccVoltage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'device_id',
        'recorded_at',
        /* ---------------------------------------------------- */
        'history_id',
        'created_at',
        'deleted_at',
        /* ---------------------------------------------------- */
        'debugging_id',
        'activation_index',
        'channel',
        'is_last',
        /* ---------------------- PAYLOAD --------------------- */
        'version',
        'panic',
        'battery',
        'vcc_voltage',
        'latitude',
        'longitude',
        'hdop',
        'gps_failure',
        'iridium_failure',
        'relay_state',
        'refresh_index',
        'air_humidity',
        'air_temperature',
        'noise',
        'pm25',
        'pm10',
        'atmospheric_pressure',
        'light_intensity',
        'wind_speed',
        'wind_direction',
        'soil_moisture',
        'soil_temperature',
        'soil_ph',
        'soil_ec',
        'rain_accumulation',
        'solar_panel_power',
        'main_battery',
        'battery_charging_current',
        'consumption',
        'battery_status'
    ];

    const UPDATED_AT = null;
    protected $primaryKey = 'recorded_at';
    public $incrementing = false;

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
        'recorded_at' => 'datetime:d/m/Y H:i:s',
        'vcc_voltage' => VccVoltage::class,
        'battery' => BatVoltage::class
    ];

    public function debug()
    {
        return $this->belongsTo(Debugging::class, 'debugging_id', 'id');
    }
}
