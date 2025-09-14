<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MqttController;
use App\Http\Controllers\Admin\MqttSettingController;
use App\Http\Controllers\General\BrokerLogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/mqtt/settings', [MqttController::class, 'settings']);
Route::post('/mqtt/store', [MqttController::class, 'store']);
Route::get('/monitoring/realtime', [MqttController::class, 'realtime']);
Route::post('/broker/log', [BrokerLogController::class, 'store']);