<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\MqttLog;
use App\Models\General\MqttSetting;
use App\Models\General\Device;

class MqttController extends Controller
{
    // ========== SIMPAN DATA DARI SUBSCRIBER ==========
    public function store(Request $request)
    {
        $validated = $request->validate([
            'topic'   => 'required|string',
            'payload' => 'required|string',
        ]);

        $parts = explode(',', $validated['payload']);

        if (count($parts) >= 5) {
            $device_id   = trim($parts[0]); // DEV-003
            $temperature = floatval(trim($parts[1])); // 30.0
            $humidity    = floatval(str_replace('%', '', trim($parts[2]))); // 85.1
            $noise       = floatval(trim($parts[3])); // 62.9
            $timestamp   = trim($parts[4]); // 2025-09-13 12:19:11

            $log = MqttLog::create([
                'topic'        => $validated['topic'],
                'payload'      => $validated['payload'], // simpan mentah juga
                'device_id'    => $device_id,
                'temperature'  => $temperature,
                'humidity'     => $humidity,
                'noise'        => $noise,
                'timestamp'    => $timestamp,
                'last_saved_at'=> now(),
            ]);
        } else {
            return response()->json(['error' => 'Invalid payload format'], 400);
        }

        return response()->json([
            'success' => true,
            'data'    => $log,
        ]);
    }

    // ========== LOAD SETTINGAN UNTUK SUBSCRIBER ==========
    public function settings()
    {
        // Ambil semua setting MQTT yang ada
        $settings = MqttSetting::with('device:id,serial_number')->get();

        return response()->json([
            'success'  => true,
            'settings' => $settings,
        ]);
    }

    public function realtime()
    {
        // Ambil data sensor terakhir (misalnya per device)
        $latest = MqttLog::select('device_id', 'temperature', 'humidity', 'noise', 'timestamp')
            ->orderBy('timestamp', 'desc')
            ->get()
            ->groupBy('device_id')
            ->map(fn($g) => $g->first());

        // Hitung rata-rata untuk ringkasan
        $avgTemp = round(MqttLog::latest()->take(10)->avg('temperature'), 1);
        $avgHum  = round(MqttLog::latest()->take(10)->avg('humidity'), 1);
        $avgNoise= round(MqttLog::latest()->take(10)->avg('noise'), 1);

        $devicesCount = Device::count();
        $alertsCount  = 0; // nanti bisa dihubungkan ke tabel alert
        $connectivity = rand(70, 99); // contoh dummy dulu

        // Format data untuk chart
        $sensors = MqttLog::orderBy('timestamp', 'desc')
            ->take(5)
            ->get()
            ->map(fn($row) => [
                'time'        => date('H:i', strtotime($row->timestamp)),
                'temperature' => $row->temperature,
                'humidity'    => $row->humidity,
                'noise'       => $row->noise ?? 0,
            ])
            ->reverse()
            ->values();

        return response()->json([
            'temperature' => $avgTemp,
            'humidity'    => $avgHum,
            'noise'       => $avgNoise,
            'devices'     => $devicesCount,
            'alerts'      => $alertsCount,
            'connectivity'=> $connectivity,
            'sensors'     => $sensors,
        ]);
    }
}
