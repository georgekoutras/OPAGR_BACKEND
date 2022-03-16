<?php

namespace App\Http\Controllers\Connectivity;

use App\Http\Controllers\Controller;

class Settings extends Controller
{

    public static function getSettings(): array
    {
        return [
            'rockblock_api' => [
                'username' => 'koutras@openit.gr',
                'password' => 'openit123!',
                'endpoint' => 'http://repl-imei.test/index.php'
            ]
        ];
    }
}
