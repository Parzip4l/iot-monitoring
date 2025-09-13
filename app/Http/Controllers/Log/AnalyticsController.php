<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\Device;
use App\Models\General\MqttLog;
use App\Models\General\Anomaly;
use App\Models\TrainManagement\TrainConfig;

class AnalyticsController extends Controller
{

    public function index()
    {
        // Bisa juga ambil device list untuk filter dropdown
        $devices = Device::all();
        $trains = TrainConfig::all();
        return view('pages.dashboard.analytics', compact('devices','trains'));
    }

    public function getData(Request $request)
    {
        // Ambil device yang ada di kereta (train_id)
        $deviceIds = [];
        if ($request->filled('train_id')) {
            $deviceIds = Device::where('train_id', $request->train_id)->pluck('id')->toArray();
        }

        // Query MQTT logs
        $logQuery = MqttLog::query()->with('device');

        // Filter per kereta (hanya device di kereta)
        if(!empty($deviceIds)){
            $serialNumbers = Device::whereIn('id', $deviceIds)->pluck('serial_number')->toArray();
            $logQuery->whereIn('device_id', $serialNumbers); // di MqttLog, device_id = serial_number
        } else {
            // Kereta tidak ada device → logs kosong
            $logQuery->whereRaw('1=0');
        }

        // Filter tanggal
        if ($request->filled('start')) {
            $logQuery->whereDate('last_saved_at', '>=', $request->start);
        }
        if ($request->filled('end')) {
            $logQuery->whereDate('last_saved_at', '<=', $request->end);
        }

        $logs = $logQuery->orderBy('last_saved_at', 'asc')->get();

        // Hitung KPI dari logs
        $avg_temperature = $logs->avg('temperature');
        $avg_humidity    = $logs->avg('humidity');
        $avg_noise       = $logs->avg('noise');

        // Query anomalies
        $anomalyQuery = Anomaly::query();
        if(!empty($deviceIds)){
            $anomalyQuery->whereIn('device_id', $deviceIds);
        } else {
            // Kereta tidak ada device → anomali kosong
            $anomalyQuery->whereRaw('1=0');
        }

        if ($request->filled('start')) {
            $anomalyQuery->whereDate('created_at','>=',$request->start);
        }
        if ($request->filled('end')) {
            $anomalyQuery->whereDate('created_at','<=',$request->end);
        }

        $anomalies = $anomalyQuery->get();
        $anomaly_count = $anomalies->count();

        // Persentase anomali per kategori sensor
        $sensorCategories = ['temperature','humidity','noise'];
        $anomalyPieData = [];
        foreach($sensorCategories as $cat){
            $count = $anomalies->where('sensor_type',$cat)->count();
            $anomalyPieData[$cat] = $anomaly_count>0 ? round(($count/$anomaly_count)*100,2) : 0;
        }

        // Group logs per device
        $devices = [];
        foreach($logs->groupBy('device_id') as $serial=>$group){
            $devices[] = [
                'serial_number'=>$serial,
                'logs'=>$group->map(fn($l)=>[
                    'temperature'=>$l->temperature,
                    'humidity'=>$l->humidity,
                    'noise'=>$l->noise,
                    'timestamp'=>$l->last_saved_at,
                ]),
            ];
        }

        return response()->json([
            'avg_temperature' => round($avg_temperature, 2),
            'avg_humidity'    => round($avg_humidity, 2),
            'avg_noise'       => round($avg_noise, 2),
            'anomaly_count'   => $anomaly_count,
            'anomaly_pie'     => $anomalyPieData,
            'logs'            => $logs,
            'devices'         => $devices,
        ]);
    }



}
