<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\Device;
use App\Models\General\BrokerLog;
use App\Models\TrainManagement\TrainConfig;
use App\Models\TrainManagement\TrainCars;
use Illuminate\Support\Facades\DB;
use App\Models\General\Anomaly;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    // Ambil semua broker terakhir per IP:PORT
    $lastLogs = BrokerLog::select('broker_logs.*')
        ->join(DB::raw('(SELECT broker_ip, broker_port, MAX(created_at) as last_time 
                         FROM broker_logs 
                         GROUP BY broker_ip, broker_port) as t'), function($join) {
            $join->on('broker_logs.broker_ip', '=', 't.broker_ip')
                ->on('broker_logs.broker_port', '=', 't.broker_port')
                ->on('broker_logs.created_at', '=', 't.last_time');
        })
        ->get()
        ->keyBy(fn($log) => $log->broker_ip . ':' . $log->broker_port);
    
    // Ambil semua device
    $devices = Device::all();

    $deviceOnline = 0;
    $deviceOffline = 0;

    foreach ($devices as $d) {
        $key = $d->broker_ip . ':' . $d->broker_port;
        if (isset($lastLogs[$key]) && $lastLogs[$key]->status === 'connected') {
            $deviceOnline++;
        } else {
            $deviceOffline++;
        }
    }
    
    $totalDevice = $devices->count();

    // Hitung kereta aktif (punya device online)
    $keretaAktif = Device::whereIn('id', function ($query) {
        $query->select('devices.id')
            ->from('devices')
            ->whereExists(function ($sub) {
                $sub->select('id')
                    ->from('broker_logs')
                    ->whereRaw('broker_logs.broker_ip = devices.broker_ip')
                    ->whereRaw('broker_logs.broker_port::text = devices.broker_port::text') // Hati-hati, ini spesifik PostgreSQL
                    ->where('broker_logs.status', 'connected');
            });
    })->distinct('train_id')->count('train_id');


    // Status sistem: kalau semua broker gagal connect → Disconnected
    $statusSistem = 'Disconnected';
    // Perbaikan: Cek apakah $lastLogs tidak kosong sebelum looping
    if ($lastLogs->isNotEmpty()) {
        foreach ($lastLogs as $log) {
            if ($log->status === 'connected') {
                $statusSistem = 'Connected';
                break;
            }
        }
    }

    // 💡 PENAMBAHAN KODE DI SINI
    // Ambil semua data kereta untuk slider di view
    $trains = TrainConfig::all();
    $alertTerbaru = Anomaly::with(['device', 'device.train', 'device.cars'])
        ->latest()
        ->take(10)
        ->get();

    return view('pages.dashboard.realtime', [
        'keretaAktif'   => $keretaAktif,
        'deviceOnline'  => $deviceOnline,
        'deviceOffline' => $deviceOffline,
        'statusSistem'  => $statusSistem,
        'trains'        => $trains,
        'alertTerbaru' => $alertTerbaru,
    ]);
}

}
