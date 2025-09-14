<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\MqttLog;
use App\Models\General\MqttSetting;
use App\Models\General\Device;
use App\Models\General\SensorThreshold;
use App\Models\General\Anomaly;

use Carbon\Carbon;

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
            $deviceSerial = trim($parts[0]); // DEV-003
            $temperature  = floatval(trim($parts[1]));
            $humidity     = floatval(str_replace('%', '', trim($parts[2])));
            $noise        = floatval(trim($parts[3]));
            $timestamp    = trim($parts[4]);

            // Cari device berdasarkan serial_number
            $device = Device::where('serial_number', $deviceSerial)->first();
            if (!$device) {
                return response()->json(['error' => 'Device not found'], 404);
            }

            $log = MqttLog::create([
                'topic'        => $validated['topic'],
                'payload'      => $validated['payload'],
                'device_id'    => $deviceSerial,
                'temperature'  => $temperature,
                'humidity'     => $humidity,
                'noise'        => $noise,
                'recorded_at'    => $timestamp,
                'last_saved_at'=> now(),
            ]);

            // Cek threshold untuk masing-masing sensor
            $sensors = ['temperature' => $temperature, 'humidity' => $humidity, 'noise' => $noise];
            foreach ($sensors as $type => $value) {
                $threshold = SensorThreshold::where('device_id', $device->id)
                    ->where('sensor_type', $type)
                    ->first();

                if ($threshold) {
                    if (($threshold->min_value !== null && $value < $threshold->min_value) ||
                        ($threshold->max_value !== null && $value > $threshold->max_value)) {
                        Anomaly::create([
                            'mqtt_log_id' => $log->id,
                            'device_id'   => $device->id,
                            'sensor_type' => $type,
                            'value'       => $value,
                            'min_value'   => $threshold->min_value,
                            'max_value'   => $threshold->max_value,
                        ]);
                    }
                }
            }

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
        $settings = MqttSetting::with('device:id,serial_number,broker_ip,broker_port')->get();

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
