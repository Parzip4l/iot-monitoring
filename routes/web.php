<?php

use Illuminate\Support\Facades\Route;
// Admin
use App\Http\Controllers\Auth\UserManagementController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\MqttSettingController;
use App\Http\Controllers\General\DashboardController;
use App\Http\Controllers\General\SystemLogController;
use App\Http\Controllers\Log\LogController;
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
            Route::get('/log', [LogController::class, 'index'])->name('log.index');
        });

        // Mqtt

        Route::prefix('mqtt')->group(function () {
            Route::get('settings', [MqttSettingController::class, 'index'])->name('mqtt.index');
            Route::get('create', [MqttSettingController::class, 'create'])->name('mqtt.create');
            Route::post('settings', [MqttSettingController::class, 'store'])->name('mqtt.store');
            Route::get('settings/{id}', [MqttSettingController::class, 'edit'])->name('mqtt.edit');
            Route::put('settings/{id}', [MqttSettingController::class, 'update'])->name('mqtt.update');
        });
        

        // System Log
        Route::get('/system-logs', [SystemLogController::class, 'index'])->name('system-logs.index');
    });

    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});

Route::get('/', [App\Http\Controllers\HomeController::class, 'root']);
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');