<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceState extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_at',
        'device_id',
        'state_text',
        'refresh_time',
        'relay_states',
        'status',
        'confirmed_at',
        'source_sent',
        'source_confirmed',
        'sat_response_id',
        'sat_success',
        'sat_error',
        'sat_response',
        'confirm_after'
    ];
    public $timestamps = false;

    /* ------------------------------------------------------------------------------------ */

    public static function confirmState($data, $refresh_time)
    {
        return $data['refresh_time'] === $refresh_time;
    }


}
