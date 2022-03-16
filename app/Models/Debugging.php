<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Debugging extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    /*
    |------------------------------------------------------------------------------------
    | Validations
    |------------------------------------------------------------------------------------
    */

    /*
    |------------------------------------------------------------------------------------
    | Relations
    |------------------------------------------------------------------------------------
    */
    public function history(){
        return $this->hasOne(History::class);
    }

    public function device(){
        return $this->belongsTo(Device::class,'device_id','id');
    }

    /*
    |------------------------------------------------------------------------------------
    | Scopes
    |------------------------------------------------------------------------------------
    */

    /*
    |------------------------------------------------------------------------------------
    | Attributes
    |------------------------------------------------------------------------------------
    */
}
