<?php


namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;


class VccVoltage implements CastsAttributes
{

    public function get($model, string $key, $value, array $attributes): float
    {
        // TODO: Implement get() method.
        $value = $value * 9;

        if ($value < 4) {
            $value = 0;
        } else {
            $value = floor($value * 10) / 10;
        }

        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value;
    }
}