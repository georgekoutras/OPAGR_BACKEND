<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:api', 'scope:see-users']);
    }

    // Return all users
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => User::all()
        ]);

    }

    // Return specific user
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // Create a user if not exists
    public function create(Request $request)
    {
        // VALIDATE THE DATA
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|unique:users|email',
            'phone' => 'required|string|size:10',
            'password' => 'required|regex:/(?=.{6,}$)(?=.*[a-z])^[a-zA-Z][a-zA-z0-9-._!]*$/',
            'confirm_password' => 'required|same:password'
        ]);

        $attributes = $request->all();

        $attributes['password'] = Hash::make($request['password']);
        $attributes = Arr::add($attributes, 'role', 'user');
        $attributes = Arr::add($attributes, 'state', 'active');

        // STORE DATA TO DATABASE
        User::create($attributes);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => "User " . $attributes['firstName'] . ' ' . $attributes['lastName'] . ' has been created!']
        ]);
    }

    // Update an existing user
    public function update(User $user, Request $request)
    {

        // VALIDATE THE DATA
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|exists:users|email',
            'phone' => 'required|string|size:10',
        ]);

        $user->update([
            'firstName' => $request['firstName'],
            'lastName' => $request['lastName'],
            'phone' => $request['phone'],
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'User has been updated successfully'
            ]
        ]);
    }

    // Delete an existing user
    public function destroy(User $user)
    {
        $user['state'] = 'inactive';
        $user->save();
        $user->delete();

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'User successfully deleted!'
            ]
        ]);
    }
}
