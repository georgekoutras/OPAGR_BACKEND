<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cultivation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'comments', 'location', 'user_id', 'cultivation_type_id'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function cultivationType()
    {
        return $this->belongsTo(CultivationType::class, 'cultivation_type_id', 'id');
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'cultivation_devices', 'cultivation_id', 'device_id');
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_users', 'cultivation_id', 'notification_id');
    }

    public function hasDevice()
    {
        return $this->hasMany(Device::class, 'cultivation_id', 'id');
    }

    // Event Handler
    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::deleting(function ($cultivation) {
            // remove cultivation and affected_cultivation id from device
            $devices = $cultivation->hasDevice()->get();

            if ($devices->isNotEmpty()) {
                $devices->each(function ($device) {
                    $device->cultivation_id = null;
                    $device->save();
                });
            }
            //destroy relationship with devices
            $cultivation->devices()->detach();

            if ($cultivation->notifications()->get()->isNotEmpty()) {
                $cultivation->notifications()->delete();
                Notification_user::where('user_id', $cultivation->id)->delete();
            }
        });
    }
}
