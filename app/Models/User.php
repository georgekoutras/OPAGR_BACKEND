<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'password',
        'phone',
        'role',
        'state',
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    /* --------------------------------------------------------------------------------- */

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    /* --------------------------------------------------------------------------------- */

    // Event Handler
    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::deleting(function ($user) {

            $cultivations = $user->cultivations()->get();

            if ($cultivations->isNotEmpty()) {
                $user->cultivations()->detach();
                $user->cultivations()->delete();
            }

            if ($user->notifications()->get()->isNotEmpty()) {
                $user->notifications()->delete();
                Notification_user::where('user_id', $user->id)->delete();
            }
        });

    }

    /* --------------------------------------------------------------------------------- */

    public function cultivations()
    {
        return $this->hasMany(Cultivation::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_users', 'user_id', 'notification_id');
    }
}