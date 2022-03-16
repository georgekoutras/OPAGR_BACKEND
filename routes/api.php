<?php

use App\Http\Controllers\Connectivity\ConnectionController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\CultivationController;
use App\Http\Controllers\CultivationTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* ---------------------------------------- CONNECTIVITY ---------------------------------------- */

Route::post('/monitor/rockblock', [ConnectionController::class, 'rockblock']);

Route::get('/monitor/gprs', [ConnectionController::class, 'gprs']);

/* ------------------------------------------- OAUTH ------------------------------------------- */

// User validation and Token generate
Route::post('/oauth2', [OAuthController::class, 'oauth_validate'])->name('login');

// Refresh token when it is expired
Route::post('/refresh', [OAuthController::class, 'refresh']);

// Log out
Route::post('/logout', [OAuthController::class, 'logout']);

/* ----------------------------------------- DASHBOARD ----------------------------------------- */

Route::get('/dashboard', [DashboardController::class, 'index']);

/* ------------------------------------------- USERS ------------------------------------------- */

// Send all users
Route::get('/users', [UserController::class, 'index']);

// Send a specific user
Route::get('/users/{user}', [UserController::class, 'show']);

// Create a user
Route::post('/users', [UserController::class, 'create']);

// Update a user
Route::put('/users/{user}', [UserController::class, 'update']);

// Delete a user
Route::delete('/users/{user}', [UserController::class, 'destroy']);

/* ------------------------------------------ DEVICES ------------------------------------------ */

// Send all devices
Route::get('/devices', [DeviceController::class, 'index']);

// Send a specific device
Route::get('/devices/{device}', [DeviceController::class, 'show']);

// Insert a device
Route::post('/devices', [DeviceController::class, 'create']);

// Update a device
Route::put('/devices/{device}', [DeviceController::class, 'update']);

// Delete a device
Route::delete('/devices/{device}', [DeviceController::class, 'destroy']);

/* ------------------------------------- CULTIVATION TYPES -------------------------------------- */

// Send all cultivation types
Route::get('/cultivation-types', [CultivationTypeController::class, 'index']);

// Send a specific cultivation type
Route::get('/cultivation-types/{type}', [CultivationTypeController::class, 'show']);

// Create a cultivation type
Route::post('/cultivation-types', [CultivationTypeController::class, 'create']);

// Update a cultivation type
Route::put('/cultivation-types/{type}', [CultivationTypeController::class, 'update']);

// Delete cultivation type
Route::delete('/cultivation-types/{type}', [CultivationTypeController::class, 'destroy']);

/* ------------------------------------- CULTIVATIONS -------------------------------------- */

// Send the cultivations
Route::get('/cultivations', [CultivationController::class, 'index']);

// Send a specific cultivation
Route::get('/cultivations/{cultivation}', [CultivationController::class, 'show']);

// Create a cultivation
Route::post('/cultivations', [CultivationController::class, 'create']);

// Update cultivation
Route::put('/cultivations/{cultivation}', [CultivationController::class, 'update']);

// Delete cultivation
Route::delete('/cultivations/{cultivation}', [CultivationController::class, 'destroy']);

/* ------------------------------------ NOTIFICATIONS ------------------------------------- */

Route::get('/notifications', [NotificationController::class, 'index']);

/* --------------------------------------- HISTORY ---------------------------------------- */

// show history of a cultivation
Route::resource('/history', HistoryController::class);

/* --------------------------------------- TEST CODE ---------------------------------------- */
