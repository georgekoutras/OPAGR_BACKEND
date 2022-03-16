<?php

namespace App\Http\Controllers;

use App\Models\CultivationType;
use App\Models\User;
use App\Rules\MaximumValue;
use App\Rules\MinimumValue;
use Illuminate\Http\Request;

class CultivationTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('scope: see-cultivation-types')->except('index');
        $this->middleware('get_user')->only('index');
    }

    // Return all cultivation types
    public function index(Request $request)
    {
        $user = User::findOrFail($request['uid']);

        switch ($user->role) {
            case 'admin':
                return response()->json([
                    'success' => true,
                    'data' => CultivationType::all()
                ]);
            case 'user':
                return response()->json([
                    'success' => true,
                    'data' => CultivationType::all()->map(function ($type) {
                        return $type->only(['id', 'type']);
                    })
                ]);
        }
    }

    // Return a specific cultivation type
    public function show(CultivationType $type)
    {
        return response()->json([
            'success' => true,
            'data' => $type
        ]);
    }

    // Create a cultivation type if it does not exists
    public function create(Request $request)
    {
        $request->validate($this->cultivationTypeValues($request));

        $attributes = $request->all();

        CultivationType::create($attributes);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'cultivation ' . $attributes['type'] . ' has been created!'
            ]
        ]);
    }

    // Update a cultivation type
    public function update(CultivationType $type, Request $request)
    {
        $request->validate($this->cultivationTypeValues($request));

        $attributes = $type->toArray();
        foreach ($attributes as $key => $value) {
            $attributes[$key] = $request[$key];
        }

        $type->update($attributes);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Cultivation has been updated!'
            ]
        ]);
    }

    // Delete a cultivation type
    public function destroy(CultivationType $type)
    {
        // check if there are active cultivations with this type
        if ($type->cultivations()->get()->isNotEmpty()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => 'There are active cultivations with this Type. Cannot be deleted'
                ]
            ]);
        }

        $type->delete();
        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Cultivation type has been deleted!'
            ]
        ]);
    }

    /* ------------------------------------------------------------------------------------------------------- */

    // cultivation min/max values for validation
    private function cultivationTypeValues($request): array
    {
        return [
            'type' => 'required|string',
            'soil_temp_min' => ['numeric', 'nullable', 'between:-40,80', new MaximumValue($request['soil_temp_max'])],
            'soil_temp_max' => ['numeric', 'nullable', 'between:-40,80', new MinimumValue($request['soil_temp_min'])],
            'soil_moist_min' => ['integer', 'nullable', 'between:0,100', new MaximumValue($request['soil_moist_max'])],
            'soil_moist_max' => ['integer', 'nullable', 'between:0,100', new MinimumValue($request['soil_moist_min'])],
            'soil_ec_min' => ['numeric', 'nullable', 'between:0,20000', new MaximumValue(($request['soil_ec_max']))],
            'soil_ec_max' => ['numeric', 'nullable', 'between:0,20000', new MinimumValue(($request['soil_ec_min']))],
            'soil_ph_min' => ['numeric', 'nullable', 'between:3,9', new MaximumValue(($request['soil_ph_max']))],
            'soil_ph_max' => ['numeric', 'nullable', 'between:3,9', new MinimumValue(($request['soil_ph_min']))],
            'air_temp_min' => ['numeric', 'nullable', 'between:-40,120', new MaximumValue($request['air_temp_max'])],
            'air_temp_max' => ['numeric', 'nullable', 'between:-40,120', new MinimumValue($request['air_temp_min'])],
            'air_hum_min' => ['integer', 'nullable', 'between:0,100', new MaximumValue($request['air_hum_max'])],
            'air_hum_max' => ['integer', 'nullable', 'between:0,100', new MinimumValue($request['air_hum_min'])],
            'atmospheric_pressure_min' => ['numeric', 'nullable', 'between:0,120', new MaximumValue($request['atmospheric_pressure_max'])],
            'atmospheric_pressure_max' => ['numeric', 'nullable', 'between:0,120', new MinimumValue($request['atmospheric_pressure_min'])],
            'light_intensity_min' => ['integer', 'nullable', 'between:0,200000', new MaximumValue($request['light_intensity_max'])],
            'light_intensity_max' => ['integer', 'nullable', 'between:0,200000', new MinimumValue($request['light_intensity_min'])],
            'noise_min' => ['numeric', 'nullable', 'between:30,120', new MaximumValue($request['noise_max'])],
            'noise_max' => ['numeric', 'nullable', 'between:30,120', new MinimumValue($request['noise_min'])],
            'pm25_min' => ['integer', 'nullable', 'between:0,1000', new MaximumValue($request['pm25_max'])],
            'pm25_max' => ['integer', 'nullable', 'between:0,1000', new MinimumValue($request['pm25_min'])],
            'pm10_min' => ['integer', 'nullable', 'between:0,1000', new MaximumValue($request['pm10_max'])],
            'pm10_max' => ['integer', 'nullable', 'between:0,1000', new MinimumValue($request['pm10_min'])],
            'wind_speed_min' => ['numeric', 'nullable', 'between:0,70', new MaximumValue($request['wind_speed_max'])],
            'wind_speed_max' => ['numeric', 'nullable', 'between:0,70', new MinimumValue($request['wind_speed_min'])],
            'rain_accumulation_min' => ['numeric', 'nullable'],
            'rain_accumulation_max' => ['numeric', 'nullable'],
        ];
    }
}
