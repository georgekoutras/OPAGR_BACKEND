<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivation_device extends Model
{
    use HasFactory;

    protected $fillable = ['cultivation_id', 'device_id'];

    protected $primaryKey = ['cultivation_id', 'device_id'];

    public $incrementing = false;

}
