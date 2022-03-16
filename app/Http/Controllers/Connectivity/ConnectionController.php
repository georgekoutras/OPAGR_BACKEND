<?php

namespace App\Http\Controllers\Connectivity;

use App\Http\Controllers\Controller;
use App\Mail\NotificationMail;
use App\Models\Cultivation;
use App\Models\Debugging;
use App\Models\Device;
use App\Models\DeviceState;
use App\Models\History;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConnectionController extends Controller
{
    /*---------------------------------------- CONNECTIVITY FUNCTIONS ---------------------------------------*/

    public function rockblock(Request $request, Device $device)
    {
        // Check if the current device id exists
        $device = $device->where('rockblock_module', true)->firstWhere('imei', $request['imei']);

        if (!$device) {
            header('HTTP/1.0 404 Not Found');
        }

        $request->validate([
            'imei' => 'required',
            'momsn' => 'required',
            'transmit_time' => 'required',
            'iridium_cep' => 'required',
            'iridium_latitude' => 'required|numeric',
            'iridium_longitude' => 'required|numeric',
            'data' => 'required|size:100'
        ]);

        // check if there is change in the refresh_time index
        if ($device->refresh_time_loading === 1) {
            $this->SATModifications($device, $request['data']);
        }

        $debug = Debugging::create([

            'device_id' => $device->id,
            'created_at' => now(),
            /*-----------------------------------------------------*/
            'imei' => $request['imei'],
            'momsn' => $request['momsn'],
            'transmit_time' => $request['transmit_time'],
            'iridium_cep' => $request['iridium_cep'],
            'iridium_latitude' => $request['iridium_latitude'],
            'iridium_longitude' => $request['iridium_longitude'],
            'data' => $request['data']
        ]);


        //split the payload and convert data
        $data = collect($this->splitAndConvert($request['data']));

        // set previous is_last values to false
        $histories = History::where('is_last', true)->get();
        foreach ($histories as $history) {
            $history->update(['is_last' => false]);
        }

        // prepare history data set to right form for DB
        $history = $this->historyData($debug->id, $device->id, 'iridium', $data);

        // Create history data
        History::create($history);

        // Compare data with cultivation types' data and create notifications
        $this->compareAndAlert($history, $device);

        // return 200 status
        return response('', '200');
    }

    public function gprs(Request $request)
    {
        // Check if the current device id exists
        $device = Device::where('gprs_module', true)->firstWhere('gprs_id', $request['i']);

        if (!$device) {
            header('HTTP/1.0 404 Not Found');
        }

        $request->validate([
            'i' => 'required',
            'd' => 'string|size:100'
        ]);

        // check if there is change in the refresh_time index
        if ($device->refresh_time_loading === 1) {
            $this->GSMmodifications($device, $request['d']);
        } else echo self::BuildResponse($device);

        $debug = Debugging::create([

            'device_id' => $device->id,
            'created_at' => now(),
            /*-----------------------------------------------------*/
            'gprs_id' => $request['i'],
            'payload' => $request['d']
        ]);

        // split the payload and convert data
        $data = collect($this->splitAndConvert($request['d']));

        // set previous is_last values to false
        $histories = History::where('is_last', true)->get();
        foreach ($histories as $history) {
            $history->update(['is_last' => false]);
        }

        // prepare history data set to right form for DB
        $history = $this->historyData($debug->id, $device->id, 'gprs', $data);

        // Create history data
        History::create($history);

        // Compare data with cultivation types' data and create notifications
        $this->compareAndAlert($history, $device);

        // return 200 status
        return response('', '200');
    }

    /*------------------------------------------ UTILITY FUNCTIONS -----------------------------------------*/

    // split the payload and convert it to readable form
    public static function splitAndConvert($index): array
    {
        $latitude = unpack('g', hex2bin(substr($index, 12, 8)))[1];
        $longitude = unpack('g', hex2bin(substr($index, 20, 8)))[1];
        $data = str_split($index, 2);

        return [
            'version' => hexdec($data[0]),
            'panic' => hexdec($data[1]),
            'batt_voltage' => hexdec($data[3] . $data[2]) / 100,
            'vcc_voltage' => hexdec($data[5] . $data[4]) / 100,
            'latitude' => floatval(number_format($latitude, 6)),
            'longitude' => floatval(number_format($longitude, 6)),
            'hdop' => hexdec($data[15] . $data[14]),
            'gps_fails' => hexdec($data[17] . $data[16]),
            'iridium_fails' => hexdec($data[19] . $data[18]),
            'relay_state' => decbin(hexdec($data[20])),
            'refresh_time' => hexdec($data[21]),
            'air_humidity' => hexdec($data[22]) / 2.5,
            'air_temperature' => unpack('s', hex2bin(substr($index, 46, 4)))[1] / 10,
            'noise' => hexdec($data[26] . $data[25]) / 10,
            'pm25' => hexdec($data[27]) / 0.25,
            'pm10' => hexdec($data[28]) / 0.25,
            'atmospheric_pressure' => hexdec($data[30] . $data[29]) / 10,
            'light_intensity' => hexdec($data[32] . $data[31]) / 0.1,
            'wind_speed' => hexdec($data[34] . $data[33]) / 10,
            'wind_direction' => hexdec($data[35]) / 0.7,
            'soil_moisture' => hexdec($data[36]) / 2.5,
            'soil_temperature' => unpack('s', hex2bin(substr($index, 74, 4)))[1] / 10,
            'soil_PH' => hexdec($data[40] . $data[39]) / 100,
            'soil_EC' => hexdec($data[42] . $data[41]) / 10,
            'rain_accumulation' => hexdec($data[44] . $data[43]) / 10,
            'pv_array_power' => hexdec($data[45]) / 10,
            'battery_voltage' => hexdec($data[46]) / 10,
            'battery_charging_current' => hexdec($data[47]) / 100,
            'load_power' => hexdec($data[48]) / 20,
            'power_status' => decbin(hexdec($data[49]))
        ];
    }

    // create the history data
    private static function historyData($debug_id, $device_id, $channel, $data): array
    {
        return [
            'device_id' => $device_id,
            'recorded_at' => now(),
            /* ---------------------------------------------------- */
            'created_at' => now(),
            /* ---------------------------------------------------- */
            'debugging_id' => $debug_id,
            'activation_index' => true,
            'channel' => $channel,
            'is_last' => true,
            /* ---------------------- PAYLOAD --------------------- */
            'version' => $data['version'],
            'panic' => $data['panic'],
            'battery' => $data['batt_voltage'],
            'vcc_voltage' => $data['vcc_voltage'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'hdop' => $data['hdop'],
            'gps_failure' => $data['gps_fails'],
            'iridium_failure' => $data['iridium_fails'],
            'relay_state' => $data['relay_state'],
            'refresh_index' => $data['refresh_time'],
            'air_humidity' => $data['air_humidity'],
            'air_temperature' => $data['air_temperature'],
            'noise' => $data['noise'],
            'pm25' => $data['pm25'],
            'pm10' => $data['pm10'],
            'atmospheric_pressure' => $data['atmospheric_pressure'],
            'light_intensity' => $data['light_intensity'],
            'wind_speed' => $data['wind_speed'],
            'wind_direction' => $data['wind_direction'],
            'soil_moisture' => $data['soil_moisture'],
            'soil_temperature' => $data['soil_temperature'],
            'soil_ph' => $data['soil_PH'],
            'soil_ec' => $data['soil_EC'],
            'rain_accumulation' => $data['rain_accumulation'],
            'solar_panel_power' => $data['pv_array_power'],
            'main_battery' => $data['battery_voltage'],
            'battery_charging_current' => $data['battery_charging_current'],
            'consumption' => $data['load_power'],
            'battery_status' => $data['power_status']
        ];
    }

    // compare data with cultivation types' data and create notifications
    private static function compareAndAlert($history, $device): void
    {
        // take all types that related to the history device
        $cultivations = $device->cultivations()->with('cultivationType')->get()->toArray();

        $notifications = array();

        // foreach cultivation check the type it has and then compare the min/max values
        foreach ($cultivations as $cultivation) {

            $messages = array();
            $notification_index = $cultivation['notification_index'];

            foreach ($cultivation['cultivation_type'] as $key => $value) {

                /* ----------------------- SOIL TEMPERATURE -----------------------*/
                if ($key === 'soil_temp_min' && !is_null($value)) {
                    if ($history['soil_temperature'] < $value) {
                        if ($notification_index[0] === '1') {
                            continue;
                        } else {
                            $notification_index[0] = '1';
                            array_push($messages, "Soil temperature for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['soil_temperature']);
                        }
                    } else {
                        $notification_index[0] = '0';
                    }
                }

                if ($key === 'soil_temp_max' && !is_null($value)) {
                    if ($history['soil_temperature'] > $value) {
                        if ($notification_index[1] === '1') {
                            continue;
                        } else {
                            $notification_index[1] = '1';
                            array_push($messages, "Soil temperature for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['soil_temperature']);
                        }
                    } else {
                        $notification_index[1] = '0';
                    }
                }

                /* ----------------------- SOIL MOISTURE -----------------------*/
                if ($key === 'soil_moist_min' && !is_null($value)) {
                    if ($history['soil_moisture'] < $value) {
                        if ($notification_index[2] === '1') {
                            continue;
                        } else {
                            $notification_index[2] = '1';
                            array_push($messages, "Soil moisture for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['soil_moisture']);
                        }
                    } else {
                        $notification_index[2] = '0';
                    }
                }

                if ($key === 'soil_moist_max' && !is_null($value)) {
                    if ($history['soil_temperature'] > $value) {
                        if ($notification_index[3] === '1') {
                            continue;
                        } else {
                            $notification_index[3] = '1';
                            array_push($messages, "Soil moisture for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['soil_moisture']);
                        }
                    } else {
                        $notification_index[3] = '0';
                    }
                }

                /* ----------------------- SOIL EC -----------------------*/
                if ($key === 'soil_ec_min' && !is_null($value)) {
                    if ($history['soil_ec'] < $value) {
                        if ($notification_index[4] === '1') {
                            continue;
                        } else {
                            $notification_index[4] = '1';
                            array_push($messages, "Soil EC for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['soil_ec']);
                        }
                    } else {
                        $notification_index[4] = '0';
                    }
                }

                if ($key === 'soil_ec_max' && !is_null($value)) {
                    if ($history['soil_ec'] > $value) {
                        if ($notification_index[5] === '1') {
                            continue;
                        } else {
                            $notification_index[5] = '1';
                            array_push($messages, "Soil EC for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['soil_ec']);
                        }
                    } else {
                        $notification_index[5] = '0';
                    }
                }

                /* ----------------------- SOIL PH -----------------------*/
                if ($key === 'soil_ph_min' && !is_null($value)) {
                    if ($history['soil_ph'] < $value) {
                        if ($notification_index[6] === '1') {
                            continue;
                        } else {
                            $notification_index[6] = '1';
                            array_push($messages, "Soil PH for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['soil_ph']);
                        }
                    } else {
                        $notification_index[6] = '0';
                    }
                }

                if ($key === 'soil_ph_max' && !is_null($value)) {
                    if ($history['soil_ph'] > $value) {
                        if ($notification_index[7] === '1') {
                            continue;
                        } else {
                            $notification_index[7] = '1';
                            array_push($messages, "Soil PH for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['soil_ph']);
                        }
                    } else {
                        $notification_index[7] = '0';
                    }
                }

                /* ----------------------- AIR TEMPERATURE -----------------------*/
                if ($key === 'air_temp_min' && !is_null($value)) {
                    if ($history['air_temperature'] < $value) {
                        if ($notification_index[8] === '1') {
                            continue;
                        } else {
                            $notification_index[8] = '1';
                            array_push($messages, "Air Temperature for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['air_temperature']);
                        }
                    } else {
                        $notification_index[8] = '0';
                    }
                }

                if ($key === 'air_temp_max' && !is_null($value)) {
                    if ($history['air_temperature'] > $value) {
                        if ($notification_index[9] === '1') {
                            continue;
                        } else {
                            $notification_index[9] = '1';
                            array_push($messages, "Air Temperature for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['air_temperature']);
                        }
                    } else {
                        $notification_index[9] = '0';
                    }
                }

                /* ----------------------- AIR HUMIDITY -----------------------*/
                if ($key === 'air_hum_min' && !is_null($value)) {
                    if ($history['air_humidity'] < $value) {
                        if ($notification_index[10] === '1') {
                            continue;
                        } else {
                            $notification_index[10] = '1';
                            array_push($messages, "Air Humidity for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['air_humidity']);
                        }
                    } else {
                        $notification_index[10] = '0';
                    }
                }

                if ($key === 'air_hum_max' && !is_null($value)) {
                    if ($history['air_humidity'] > $value) {
                        if ($notification_index[11] === '1') {
                            continue;
                        } else {
                            $notification_index[11] = '1';
                            array_push($messages, "Air Humidity for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['air_humidity']);
                        }
                    } else {
                        $notification_index[11] = '0';
                    }
                }

                /* ----------------------- ATMOSPHERIC PRESSURE -----------------------*/
                if ($key === 'atmospheric_pressure_min' && !is_null($value)) {
                    if ($history['atmospheric_pressure'] < $value) {
                        if ($notification_index[12] === '1') {
                            continue;
                        } else {
                            $notification_index[12] = '1';
                            array_push($messages, "Atmospheric pressure for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['atmospheric_pressure']);
                        }
                    } else {
                        $notification_index[12] = '0';
                    }
                }

                if ($key === 'atmospheric_pressure_max' && !is_null($value)) {
                    if ($history['atmospheric_pressure'] > $value) {
                        if ($notification_index[13] === '1') {
                            continue;
                        } else {
                            $notification_index[13] = '1';
                            array_push($messages, "Atmospheric pressure for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['atmospheric_pressure']);
                        }
                    } else {
                        $notification_index[13] = '0';
                    }
                }

                /* ----------------------- LIGHT INTENSITY -----------------------*/
                if ($key === 'light_intensity_min' && !is_null($value)) {
                    if ($history['light_intensity'] < $value) {
                        if ($notification_index[14] === '1') {
                            continue;
                        } else {
                            $notification_index[14] = '1';
                            array_push($messages, "Light Intensity for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['light_intensity']);
                        }
                    } else {
                        $notification_index[14] = '0';
                    }
                }

                if ($key === 'light_intensity_max' && !is_null($value)) {
                    if ($history['light_intensity'] > $value) {
                        if ($notification_index[15] === '1') {
                            continue;
                        } else {
                            $notification_index[15] = '1';
                            array_push($messages, "Light Intensity for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['light_intensity']);
                        }
                    } else {
                        $notification_index[15] = '0';
                    }
                }

                /* ----------------------- NOISE -----------------------*/
                if ($key === 'noise_min' && !is_null($value)) {
                    if ($history['noise'] < $value) {
                        if ($notification_index[16] === '1') {
                            continue;
                        } else {
                            $notification_index[16] = '1';
                            array_push($messages, "Noise for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['noise']);
                        }
                    } else {
                        $notification_index[16] = '0';
                    }
                }

                if ($key === 'noise_max' && !is_null($value)) {
                    if ($history['noise'] > $value) {
                        if ($notification_index[17] === '1') {
                            continue;
                        } else {
                            $notification_index[17] = '1';
                            array_push($messages, "Noise for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['noise']);
                        }
                    } else {
                        $notification_index[17] = '0';
                    }
                }

                /* ----------------------- PM 2.5 -----------------------*/
                if ($key === 'pm25_min' && !is_null($value)) {
                    if ($history['pm25'] < $value) {
                        if ($notification_index[18] === '1') {
                            continue;
                        } else {
                            $notification_index[18] = '1';
                            array_push($messages, "PM 2.5 for for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['pm25']);
                        }
                    } else {
                        $notification_index[18] = '0';
                    }
                }

                if ($key === 'pm25_max' && !is_null($value)) {
                    if ($history['pm25'] > $value) {
                        if ($notification_index[19] === '1') {
                            continue;
                        } else {
                            $notification_index[19] = '1';
                            array_push($messages, "PM 2.5 for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['pm25']);
                        }
                    } else {
                        $notification_index[19] = '0';
                    }
                }

                /* ----------------------- PM 10 -----------------------*/
                if ($key === 'pm10_min' && !is_null($value)) {
                    if ($history['pm10'] < $value) {
                        if ($notification_index[20] === '1') {
                            continue;
                        } else {
                            $notification_index[20] = '1';
                            array_push($messages, "PM 10 for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['pm10']);
                        }
                    } else {
                        $notification_index[20] = '0';
                    }
                }

                if ($key === 'pm10_max' && !is_null($value)) {
                    if ($history['pm10'] > $value) {
                        if ($notification_index[21] === '1') {
                            continue;
                        } else {
                            $notification_index[21] = '1';
                            array_push($messages, "PM 10 for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['pm10']);
                        }
                    } else {
                        $notification_index[21] = '0';
                    }
                }

                /* ----------------------- WIND SPEED -----------------------*/
                if ($key === 'wind_speed_min' && !is_null($value)) {
                    if ($history['wind_speed'] < $value) {
                        if ($notification_index[22] === '1') {
                            continue;
                        } else {
                            $notification_index[22] = '1';
                            array_push($messages, "Wind Speed for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['wind_speed']);
                        }
                    } else {
                        $notification_index[22] = '0';
                    }
                }

                if ($key === 'wind_speed_max' && !is_null($value)) {
                    if ($history['wind_speed'] > $value) {
                        if ($notification_index[23] === '1') {
                            continue;
                        } else {
                            $notification_index[23] = '1';
                            array_push($messages, "Wind Speed for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['wind_speed']);
                        }
                    } else {
                        $notification_index[23] = '0';
                    }
                }

                /* ----------------------- RAIN ACCUMULATION -----------------------*/
                if ($key === 'rain_accumulation_min' && !is_null($value)) {
                    if ($history['rain_accumulation'] < $value) {
                        if ($notification_index[24] === '1') {
                            continue;
                        } else {
                            $notification_index[24] = '1';
                            array_push($messages, "Rain accumulation for " . $cultivation['cultivation_type']['type'] . " type is below minimum value ($value)! Current: " . $history['rain_accumulation']);
                        }
                    } else {
                        $notification_index[24] = '0';
                    }
                }

                if ($key === 'rain_accumulation_max' && !is_null($value)) {
                    if ($history['rain_accumulation'] > $value) {
                        if ($notification_index[25] === '1') {
                            continue;
                        } else {
                            $notification_index[25] = '1';
                            array_push($messages, "Rain accumulation for " . $cultivation['cultivation_type']['type'] . " type is above maximum value ($value)! Current: " . $history['rain_accumulation']);
                        }
                    } else {
                        $notification_index[25] = '0';
                    }
                }

            }

            // update notification_index
            $temp = Cultivation::find($cultivation['id']);
            $temp->notification_index = $notification_index;
            $temp->save();

            // collect messages based the cultivation type
            if (!empty($messages)) {
                $notifications[$cultivation['cultivation_type']['id']] = $messages;
            }
        }

        // create both notification based the cultivation type of each user's cultivation and notification_user notification
        // Threshold notifications
        $message_to = collect();
        foreach ($notifications as $type_id => $values) {
            foreach ($values as $message) {

                // create the notification
                $notification = Notification::create([
                    'created_at' => now(),
                    'type' => 'threshold',
                    'device_id' => $device->id,
                    'message' => $message
                ]);

                foreach ($cultivations as $cultivation) {
                    // Create relation between a cultivation and a user
                    if ($cultivation['cultivation_type_id'] === $type_id) {
                        $notification->cultivations()->attach($cultivation['id'], ['user_id' => $cultivation['user_id']]);
                        $message_to->add([$cultivation['user_id'], $notification['id']]);
                    }
                }
            }
        }

        // Functional Notifications //|\\ - UNDER CONSTRUCTION -

        // Create notification email for each user
        $message_to = $message_to->mapToGroups(function ($value) {
            return [$value[0] => $value[1]];
        });

        $message_to->each(function ($notifications, $user_id) {
            $user = User::find($user_id);
            $notifications = $notifications->map(function ($notification_id, $key) {
                return [$key => Notification::find($notification_id)];
            })->flatten();

            //SEND EMAIL
            Mail::to($user)->send(new NotificationMail($notifications, $user));
        });
    }

    // check if there is change in the refresh_time index at gprs channel
    private static function GSMmodifications($device, $payload): void
    {
        $callData = self::splitAndConvert($payload);

        if (!is_null($device->pending_state_id)) {
            $present_state = DeviceState::firstWhere('id', $device->pending_state_id);
            if ($present_state && $present_state['confirm_after'] === 0) {
                $confirmed = DeviceState::confirmState($callData, $present_state['refresh_time'], $present_state['relay_states']);
                if ($confirmed) {
                    // create a confirm deviceState in Database
                    $confirmed_state = new DeviceState();
                    $temp_data = $present_state->toArray();
                    unset($temp_data['id']);
                    $temp_data['created_at'] = now();
                    $confirmed_state = $confirmed_state->create($temp_data);
                    $confirmed_state->update(['status' => 1, 'confirmed_at' => now(), 'source_confirmed' => 'gsm', 'confirm_after' => -1]);

                    // update device confirmation indexes
                    $deviceObject = Device::find($device->id);
                    $deviceObject->update([
                        'confirmed_state_id' => $confirmed_state->id,
                        'pending_state_id' => null,
                        'relays_loading' => "00000000",
                        'refresh_time_loading' => 0,
                    ]);
                    /* --------------------------------------------------------------------- */
                    $device->confirmed_state_id = $device->pending_state_id;
                    $device->pending_state_id = null;
                    /* --------------------------------------------------------------------- */
                    echo self::BuildResponse($device);
                    return; // confirmed
                } else {
                    echo self::BuildResponse($device);
                    return; // unconfirmed
                }
            }
        }

        $response_text = self::BuildResponse($device);
        echo $response_text; // 100 character string

        // Send RockBlock Message if we have HYBRID connectivity
        if ($device->rockblock_module) {
            self::sendRockBlockMessage($device, "", 'yes');
        }

        $old_state = null;

        // if there is confirmed_state_id then find it in the device_state table and hold it as the old_state
        if (!is_null($device->confirmed_state_id)) {
            $states = DeviceState::where('id', $device->confirmed_state_id)->get();
            if ($states) {
                $old_state = $states[0];
            }
        }

        if ($old_state === null || $old_state['refresh_time'] !== $device->refresh_index || $old_state['relay_states'] !== $device->relay_state) {

            $deviceState = new DeviceState();
            $deviceState['device_id'] = $device->id;
            $deviceState['state_text'] = $response_text;
            $deviceState['refresh_time'] = $device->refresh_index;
            $deviceState['relay_states'] = $device->relay_states;
            $deviceState['status'] = 0;
            $deviceState['source_sent'] = 'gsm';
            $deviceState['confirm_after'] = 0;
            $deviceState['created_at'] = now();
            if ($deviceState->save()) {
                $device->pending_state_id = $deviceState['id'];
                $deviceObject = Device::find($device->id);
                $deviceObject['pending_state_id'] = $deviceState['id'];
                $deviceObject->save();
            }
        }
    }

    // check if there is change in the refresh_time index at iridium channel
    private static function SATmodifications($device, $payload): void
    {
        $callData = self::splitAndConvert($payload);

        if (!is_null($device->pending_state_id)) {

            $present_state = DeviceState::firstWhere('id', $device->pending_state_id);

            if ($present_state && $present_state->confirm_after === 0) {

                $confirmed = DeviceState::confirmState($callData, $present_state['refresh_time']);

                if ($confirmed) {
                    // create a confirm deviceState in Database
                    $confirmed_state = new DeviceState();
                    $temp_data = $present_state->toArray();
                    unset($temp_data['id']);
                    $temp_data['created_at'] = now();
                    $confirmed_state = $confirmed_state->create($temp_data);
                    $confirmed_state->update(['status' => 1, 'confirmed_at' => now(), 'source_confirmed' => 'sat', 'confirm_after' => -1]);

                    // update device confirmation indexes
                    $deviceObject = Device::find($device->id);
                    $deviceObject->update([
                        'confirmed_state_id' => $confirmed_state->id,
                        'pending_state_id' => null,
                        'relays_loading' => "00000000",
                        'refresh_time_loading' => 0,
                    ]);
                    /* --------------------------------------------------------------------- */
                    $device->confirmed_state_id = $device->pending_state_id;
                    $device->pending_state_id = null;
                    /* --------------------------------------------------------------------- */
                    return; // confirmed
                } else {
                    return; // unconfirmed
                }
            } elseif ($present_state && $present_state->confirm_after === 1) {

                $temp_data = $present_state->toArray();
                unset($temp_data['id']);
                $temp_data['confirm_after'] = 0;
                $newState = new DeviceState();
                $newState = $newState->create($temp_data);

                // update device confirmation indexes
                $deviceObject = Device::find($device->id);
                $deviceObject->update(['pending_state_id' => $newState->id]);

                return; // pending 2
            }
        }


        $response_text = self::BuildResponse($device);

        $result = self::sendRockBlockMessage($device, $response_text);

        $old_state = null;

        // if there is confirmed_state_id then find it in the device_state table and hold it as the old_state
        if (!is_null($device->confirmed_state_id)) {
            $states = DeviceState::where('id', $device->confirmed_state_id)->get();
            if ($states) {
                $old_state = $states[0];
            }
        }

        if ($result['success']) {
            if ($old_state === null || $old_state['refresh_time'] !== $device->refresh_index || $old_state['relay_states'] !== $device->relay_state) {
                $deviceState = new DeviceState();
                $deviceState['device_id'] = $device->id;
                $deviceState['state_text'] = $response_text;
                $deviceState['refresh_time'] = $device->refresh_index;
                $deviceState['relay_states'] = $device->relay_states;
                $deviceState['status'] = 0;
                $deviceState['source_sent'] = 'sat';
                $deviceState['confirm_after'] = 1;
                $deviceState['created_at'] = now();
                //$deviceState['sat_response_id'] = substr($result['response'], '4');
                $deviceState['sat_success'] = $result['success'];
                $deviceState['sat_response'] = $result['response'];
                if ($deviceState->save()) {
                    $device->pending_state_id = $deviceState['id'];
                    $deviceObject = Device::find($device->id);
                    $deviceObject['pending_state_id'] = $deviceState['id'];
                    $deviceObject->save();
                }
            }
        }
    }

    // send message to the satellite
    private static function sendRockBlockMessage($device, $data = "", $flush = "no"): array
    {
        $settings = Settings::getSettings();
        $rockblockSettings = $settings['rockblock_api'];

        $url = $rockblockSettings['endpoint'];
        $username = $rockblockSettings['username'];
        $password = $rockblockSettings['password'];

        $postField = "imei=" . $device->imei . "&username=" . $username . "&password=" . $password . "&data=" . $data . "&flush=" . $flush;

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $postField
        ]);

        $result['data'] = curl_exec($curl);
        $result['code'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'success' => $result['code'] === 200,
            'code' => $result['code'],
            'response' => $result['data']
        ];
    }

    // build the response Text for the device
    private static function BuildResponse($device): string
    {
        // initialize response text
        $res = Device::buildInitialResponse();

        // insert the updated refresh index and convert it to HEX
        $res[0] = dechex($device->refresh_index);
        if (strlen($res[0]) == 1) {
            $res[0] = "0" . $res[0];
        }

        // insert relay state and convert it to HEX
        $res[1] = dechex(bindec($device->relay_states));
        if (strlen($res[1]) == 1) {
            $res[1] = "0" . $res[1];
        }

        // convert array response text to string and print it
        return implode('', $res);
    }
}
