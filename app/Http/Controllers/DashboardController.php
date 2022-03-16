<?php

namespace App\Http\Controllers;

use App\Models\Cultivation;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:see-dashboard', 'get_user']);
    }

    public function index(Request $request)
    {
        $user = User::findOrFail($request['uid']);
        $devices_data = collect();
        $cultivations_data = collect();

        switch ($user->role) {

            case 'admin':

                // collect all devices with the last history record
                Device::all()->each(function ($device) use ($devices_data) {
                    $device->load(['history' => function ($query) {
                        $query->orderByDesc('recorded_at')->first();
                    }]);
                    $devices_data->push($device);
                });


                Cultivation::all()->each(function ($cultivation) use ($cultivations_data) {

                    // load latest history from cultivations' devices
                    $cultivation->load(['user', 'cultivationType', 'devices.history' => function ($query) {
                        $query->orderByDesc('recorded_at')->first();
                    }]);

                    // clear devices with empty history
                    $filtered = $cultivation->devices->reject(function ($device) {
                        return $device->history->isEmpty();
                    })->values();

                    unset($cultivation->devices);
                    $cultivation->devices = $filtered->toArray();

                    $cultivations_data->push($cultivation);
                });

                return response()->json([
                    'success' => true,
                    'data' => [
                        'devices' => $devices_data,
                        'cultivations' => $cultivations_data
                    ]
                ]);

            case 'user':

                $cultivations = $user->cultivations()->get();

                $cultivations->each(function ($cultivation) use ($cultivations_data, $devices_data) {

                    // take devices which are inside the cultivation
                    $cul_devices = $cultivation->hasDevice()->get();

                    // fetch the last inserted history for the device
                    $cul_devices->each(function ($device) {
                        $device->load(['history' => function ($query) {
                            $query->orderByDesc('recorded_at')->first();
                        }]);
                    });

                    // load latest history from cultivations' devices
                    $cultivation->load(['cultivationType', 'devices.history' => function ($query) {
                        $query->orderByDesc('recorded_at')->first();
                    }]);

                    // clear devices with empty history
                    $filtered = $cultivation->devices->reject(function ($device) {
                        return $device->history->isEmpty();
                    })->values();

                    unset($cultivation->devices);
                    $cultivation->devices = $filtered->toArray();

                    $cultivations_data->push($cultivation);
                    $devices_data->push($cul_devices);
                });

                return response()->json([
                    'success' => true,
                    'data' => [
                        'cultivations' => $cultivations_data,
                        'devices' => $devices_data->flatten()
                    ]
                ]);
        }
    }
}
