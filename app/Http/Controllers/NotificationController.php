<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:see-notifications', 'get_user']);
    }

    public function index(Request $request)
    {
        $user = User::findOrFail($request['uid']);



        switch ($user->role) {

            case 'admin' :
                return response()->json([
                    'success' => true,
                    'data' => Notification::with(['cultivations.user:id,firstName,lastName'])->orderByDesc('created_at')->paginate(10)
                ]);

            case 'user':
                return response()->json([
                    'success' => true,
                    'data' => $user->notifications()->with(['cultivations' => function ($query) use ($user) {
                        $query->where('cultivations.user_id', $user->id);
                    }])->orderByDesc('created_at')->paginate(10)
                ]);
        }
    }
}
