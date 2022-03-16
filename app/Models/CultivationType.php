<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CultivationType extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = ['id','created_at','updated_at','deleted_at'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
        'updated_at' => 'datetime:d/m/Y H:i:s',
    ];

    public function cultivations(){
        return $this->hasMany(Cultivation::class);
    }

}
