<?php

use Illuminate\Support\Facades\Route;
// Admin
use App\Http\Controllers\Auth\UserManagementController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\MqttSettingController;
use App\Http\Controllers\General\DashboardController;
use App\Http\Controllers\General\SystemLogController;
use App\Http\Controllers\General\SensorThresholdController;
use App\Http\Controllers\Admin\MqttController;

// Setting
use App\Http\Controllers\General\BrokerLogController;

// Dashboard dan Analytics
use App\Http\Controllers\Log\LogController;
use App\Http\Controllers\Log\AnalyticsController;

// Train Config
use App\Http\Controllers\TrainManagement\TrainController;
use App\Http\Controllers\TrainManagement\CarsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|

*/


// Auth
Route::get('/login', [UserManagementController::class, 'showLogin'])->name('login');
Route::post('/login', [UserManagementController::class, 'login'])->name('login.post');
Route::post('/logout', [UserManagementController::class, 'logout'])->name('logout');

// Admin User Management
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserManagementController::class);
    });
    
    // Settings
    Route::prefix('settings')->group(function () {
        // roles
        Route::prefix('roles')->group(function () {
            Route::get('/index', [RoleController::class, 'index'])->name('role.index');
            Route::get('/create', [RoleController::class, 'create'])->name('role.create');
            Route::post('/store', [RoleController::class, 'store'])->name('role.store');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('role.update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('role.destroy');
        });

        Route::prefix('devices')->group(function () {
            Route::get('/index', [DeviceController::class, 'index'])->name('device.index');
            Route::get('/create', [DeviceController::class, 'create'])->name('device.create');
            Route::post('/store', [DeviceController::class, 'store'])->name('device.store');
            Route::get('/{device}/edit', [DeviceController::class, 'edit'])->name('device.edit');
            Route::put('/{device}', [DeviceController::class, 'update'])->name('device.update');
            Route::get('/log', [LogController::class, 'index'])->name('log.index');
            Route::get('log/data', [LogController::class, 'getData'])->name('log.data');
            Route::delete('/{device}', [DeviceController::class, 'destroy'])->name('device.destroy');
        });

        // Mqtt
        Route::prefix('mqtt')->group(function () {
            Route::get('settings', [MqttSettingController::class, 'index'])->name('mqtt.index');
            Route::delete('settings/{id}', [MqttSettingController::class, 'destroy'])->name('mqtt.destroy');
            Route::get('create', [MqttSettingController::class, 'create'])->name('mqtt.create');
            Route::post('settings', [MqttSettingController::class, 'store'])->name('mqtt.store');
            Route::get('settings/{id}', [MqttSettingController::class, 'edit'])->name('mqtt.edit');
            Route::put('settings/{id}', [MqttSettingController::class, 'update'])->name('mqtt.update');
        });

        Route::prefix('train-config')->group(function () {
            Route::get('/index', [TrainController::class, 'index'])->name('train.config.index');
            Route::get('/create', [TrainController::class, 'create'])->name('train.config.create');
            Route::post('/store', [TrainController::class, 'store'])->name('train.config.store');
            Route::get('/{train}/edit', [TrainController::class, 'edit'])->name('train.config.edit');
            Route::put('/{train}', [TrainController::class, 'update'])->name('train.config.update');
            Route::delete('/{train}', [TrainController::class, 'destroy'])->name('train.config.destroy');
            Route::get('/train/{train}/cars', [TrainController::class, 'getCars'])->name('train.config.cars');
        });

        Route::prefix('cars-config')->group(function () {
            Route::get('/index', [CarsController::class, 'index'])->name('cars.config.index');
            Route::get('/create', [CarsController::class, 'create'])->name('cars.config.create');
            Route::post('/store', [CarsController::class, 'store'])->name('cars.config.store');
            Route::get('/{cars}/edit', [CarsController::class, 'edit'])->name('cars.config.edit');
            Route::put('/{cars}', [CarsController::class, 'update'])->name('cars.config.update');
            Route::delete('/{cars}', [CarsController::class, 'destroy'])->name('cars.config.destroy');
        });

        Route::resource('sensor-threshold', SensorThresholdController::class);
        Route::get('/anomali-data', [SensorThresholdController::class, 'indexAnomaly'])->name('anomaly.index');

        // System Log
        Route::get('/system-logs', [SystemLogController::class, 'index'])->name('system-logs.index');
        Route::get('/broker-status', [BrokerLogController::class, 'index'])->name('broker.index');
    });

    Route::prefix('dashboard')->group(function () {
        Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/iot/data', [AnalyticsController::class, 'getData'])->name('analytics.iot.data');
    });

    Route::get('/reports/daily/download', [MqttController::class, 'downloadDaily'])->name('reports.daily.download');
    Route::get('/reports/monthly/download', [MqttController::class, 'downloadMonthly'])->name('reports.monthly.download');
    Route::get('/reports/range/download', [MqttController::class, 'downloadRange'])->name('reports.range.download');

    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'root']);
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');