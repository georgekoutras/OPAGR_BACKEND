<?php

namespace App\Http\Controllers;

use App\Models\Cultivation;
use App\Models\Cultivation_device;
use App\Models\History;
use App\Models\User;
use Illuminate\Http\Request;

class CultivationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:see-cultivations', 'get_user']);
    }

    // Return the cultivations
    public function index(Request $request)
    {
        $user = User::findOrFail($request['uid']);

        switch ($user->role) {
            case 'admin':
                return response()->json([
                    'success' => true,
                    'data' => Cultivation::with(['user:id,firstName,lastName', 'cultivationType', 'devices'])->get()
                ]);
            case 'user':
                return response()->json([
                    'success' => true,
                    'data' => $user->cultivations()->with('cultivationType')->get()
                ]);
        }
    }

    // Return specific cultivation
    public function show(Cultivation $cultivation, Request $request)
    {
        $user = User::findOrFail($request['uid']);
        $history = new History();

        // check if cultivation exists on user's cultivations
        if ($user->role === 'user') {
            $cultivation = $user->cultivations()->findOrFail($cultivation['id']);
        } elseif ($user->role === 'admin') {
            $cultivation = $cultivation->load(['user']);
        }

        try {
            $devices = Cultivation_device::where('cultivation_id', $cultivation->id)->pluck('device_id')->toArray();
            $history = $history->whereIn('device_id', $devices)->orderByDesc('recorded_at')->paginate(10);

        } catch (\Exception $e){
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => $e->getMessage()
                ]
            ], $e->getCode());

        }

        return response()->json([
            'success' => true,
            'data' => [
                'cultivation' => $cultivation,
                'history' => $history
            ]
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|numeric',
            'name' => 'required|string',
            'comments' => 'required|string',
            'type_id' => 'required|exists:cultivation_types,id|numeric',
            'location' => 'required|JSON'
        ]);

        $attributes = [
            'name' => $request['name'],
            'comments' => $request['comments'],
            'user_id' => $request['user_id'],
            'cultivation_type_id' => $request['type_id'],
            'location' => $request['location']
        ];

        Cultivation::create($attributes);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Cultivation stored successfully to database!'
            ]
        ]);
    }

    public function update(Cultivation $cultivation, Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|numeric',
            'name' => 'required|string',
            'comments' => 'required|string',
            'type_id' => 'required|exists:cultivation_types,id|numeric',
            'location' => 'required|JSON'
        ]);

        $attributes = [
            'name' => $request['name'],
            'comments' => $request['comments'],
            'user_id' => $request['user_id'],
            'cultivation_type_id' => $request['type_id'],
            'location' => $request['location']
        ];

        $cultivation->update($attributes);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Cultivation ' . $request['name'] . ' has been updated successfully!'
            ]
        ]);

    }

    public function destroy(Cultivation $cultivation)
    {
        $cultivation->delete();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Cultivation has successfully deleted!'
            ]
        ]);
    }
}
