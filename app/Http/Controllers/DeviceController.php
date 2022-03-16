<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\User;
use App\Rules\cultivationHasUser;
use App\Rules\refreshTimeLoading;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use function PHPUnit\Framework\isEmpty;

class DeviceController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:see-devices', 'get_user']);
    }

    // Return the devices
    public function index(Request $request)
    {
        $user = User::where('id', $request['uid'])->firstOrFail();

        switch ($user->role) {
            case 'admin':
                return response()->json([
                    'success' => true,
                    'data' => Device::all()
                ]);

            case 'user':
                $cultivations = $user->cultivations()->get();

                $devices = new Collection();

                $cultivations->map(function ($cultivation) use ($devices) {
                    $devices->push($cultivation->devices()->get());
                });

                return response()->json([
                    'success' => true,
                    'data' => $devices->flatten()->unique('id')->values()
                ]);
        }
    }

    // Return a specific device

    /**
     * @throws Throwable
     */
    public function show(Device $device, Request $request)
    {
        $user = User::findOrFail($request['uid']);

        switch ($user->role) {
            case 'admin':

                $cultivation = $device->cultivations()->get();
                $history = $device->history()->orderByDesc('recorded_at')->paginate(10);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'cultivation' => $cultivation,
                        'history' => $history,
                        'device' => $device
                    ]
                ]);

            case 'user':

                throw_if($device->cultivations()->where('user_id', $request['uid'])->get()->isEmpty(), new HttpException(404));

                $cultivation = $device->cultivations()->get();
                $history = $device->history()->orderByDesc('recorded_at')->paginate(10);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'cultivation' => $cultivation,
                        'history' => $history,
                        'device' => $device
                    ]
                ]);
        }
    }

    /**
     * @throws Throwable
     */
    public function create(Request $request)
    {
        $user = User::findOrFail($request['uid']);

        throw_if($user->role === 'user', new HttpException(403, 'Forbidden'));

        if (!isset($request['affected_cultivations'])) {
            $request['affected_cultivations'] = [];
        }

        $request->validate([
            'name' => 'required|string',
            'cultivation_id' => new cultivationHasUser,
            'affected_cultivations' => new cultivationHasUser,
            'rockblock_module' => ['required', 'string', Rule::in(['true', 'false'])],
            'imei' => Rule::requiredIf($request['rockblock_module'] === 'true'),
            'gprs_module' => ['required', 'string', Rule::in(['true', 'false'])],
            'gprs_id' => Rule::requiredIf($request['gprs_module'] === 'true'),
            'iccid' => Rule::requiredIf($request['gprs_module'] === 'true'),
            'msisdn' => Rule::requiredIf($request['gprs_module'] === 'true'),
            'imsi' => Rule::requiredIf($request['gprs_module'] === 'true'),
            'version' => ['required', Rule::in(['2', '3'])],
            'refresh_index' => 'required|numeric|between:0,8'
        ]);

        $data = [
            'name' => $request['name'],
            'cultivation_id' => $request['cultivation_id'],
            'state' => 'active',
            'rockblock_module' => !empty($request['imei']),
            'imei' => $request['imei'],
            'gprs_module' => !empty($request['gprs_id']),
            'gprs_id' => $request['gprs_id'],
            'iccid' => $request['iccid'],
            'msisdn' => $request['msisdn'],
            'imsi' => $request['imsi'],
            'version' => $request['version'],
            'refresh_index' => $request['refresh_index']
        ];

        $device = Device::create($data);

        // add to affected cultivation the cultivation's id that device is located
        $data = $request['affected_cultivations'];
        if (!is_null($request['cultivation_id'])) {
            array_push($data, $request['cultivation_id']);
        }

        // create cultivation - device relation if there are affected_cultivations
        if (!empty($data)) {
            $device->cultivations()->attach($data);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Device has been successfully added to database.'
            ]
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(Device $device, Request $request)
    {
        $user = User::findOrFail($request['uid']);
        throw_if($user->role === 'user', new HttpException(403, 'Forbidden'));

        if (!isset($request['affected_cultivations'])) {
            $request['affected_cultivations'] = [];
        }

        if ($device['refresh_time_loading'] === 1 && $device['refresh_index'] !== $request['refresh_index']) {
            $refresh_index_error = true;
        } else {
            $refresh_index_error = false;
        }

        $request->validate([
            'name' => 'required|string',
            'rockblock_module' => ['required', 'string', Rule::in(['true', 'false'])],
            'cultivation_id' => new cultivationHasUser,
            'affected_cultivations' => new cultivationHasUser,
            'imei' => Rule::requiredIf($request['rockblock_module'] === 'true'),
            'gprs_module' => ['required', 'string', Rule::in(['true', 'false'])],
            'gprs_id' => Rule::requiredIf($request['gprs_module'] === 'true'),
            'iccid' => Rule::requiredIf($request['gprs_module'] === 'true'),
            'msisdn' => Rule::requiredIf($request['gprs_module'] === 'true'),
            'imsi' => Rule::requiredIf($request['gprs_module'] === 'true'),
            'version' => ['required', Rule::in(['2', '3'])],
            'refresh_index' => ['required', 'numeric', 'between:0,8', new refreshTimeLoading($refresh_index_error)]
        ]);

        $attributes = [
            'name' => $request['name'],
            'state' => 'active',
            'cultivation_id' => $request['cultivation_id'],
            'rockblock_module' => !empty($request['imei']),
            'imei' => $request['imei'],
            'gprs_module' => !empty($request['gprs_id']),
            'gprs_id' => $request['gprs_id'],
            'iccid' => $request['iccid'],
            'msisdn' => $request['msisdn'],
            'imsi' => $request['imsi'],
            'version' => $request['version'],
        ];


        if (!$device['refresh_time_loading']) {
            $attributes['refresh_index'] = $request['refresh_index'];
        }

        // check if the refresh index has changed
        if ($device['refresh_index'] !== $request['refresh_index']) {
            $attributes['last_status_update'] = now();
            $attributes['refresh_time_loading'] = 1;
        }

        $device->update($attributes);

        // add to affected cultivation the cultivation's id that device is located
        $data = $request['affected_cultivations'];
        if (!is_null($request['cultivation_id'])) {
            array_push($data, $request['cultivation_id']);
        }

        // delete all affected_cultivations and insert the new ones
        $device->cultivations()->detach();
        if (!empty($data)) {
            $device->cultivations()->attach($data);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Device has been successfully updated.'
            ]
        ]);
    }


    /**
     * @throws Throwable
     */
    public function destroy(Device $device, Request $request)
    {
        $user = User::findOrFail($request['uid']);
        throw_if($user->role === 'user', new HttpException(403, 'Forbidden'));

        $device->state = 'inactive';
        $device->save();
        $device->delete();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Device has been successfully deleted from database.'
            ]
        ]);
    }
}
