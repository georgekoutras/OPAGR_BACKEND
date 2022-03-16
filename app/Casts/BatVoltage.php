<?php


namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class BatVoltage implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes): float
    {
        // TODO: Implement get() method.
        return $value * 2;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        // TODO: Implement set() method.
        return $value;
    }
}