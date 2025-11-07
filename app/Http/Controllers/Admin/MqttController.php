<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\MqttLog;
use App\Models\General\MqttSetting;
use App\Models\General\Device;
use App\Models\General\SensorThreshold;
use App\Models\General\Anomaly;
use App\Models\TrainManagement\TrainConfig;
use App\Models\TrainManagement\TrainCars;

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
            $rawNoise     = floatval(trim($parts[3]));
            $timestamp    = trim($parts[4]);

            $I = $rawNoise / 1000;
            $noise = 30 + (4.5 * $I);

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
                'noise'        => round($noise, 1),
                'timestamp'    => $timestamp,
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

    public function realtime(Request $request)
    {
        $trainId = $request->query('train_id');
        $deviceSerials = [];

        // 🔹 Ambil daftar serial_number device berdasarkan train_id
        if ($trainId) {
            $deviceSerials = Device::whereHas('trainCar', function ($query) use ($trainId) {
                    $query->where('train_id', $trainId);
                })
                ->pluck('serial_number')
                ->toArray();
        }

        // 🔹 Siapkan query dasar
        $logQuery = MqttLog::query();
        $deviceQuery = Device::query();

        // 🔹 Terapkan filter jika kereta dipilih
        if ($trainId && !empty($deviceSerials)) {
            $logQuery->whereIn('device_id', $deviceSerials);
            $deviceQuery->whereIn('serial_number', $deviceSerials);
        } elseif ($trainId && empty($deviceSerials)) {
            // Jika kereta dipilih tapi tidak ada device, kembalikan kosong
            $logQuery->whereRaw('1 = 0');
        }

        // 🔹 Ambil data paling baru (gunakan ID untuk akurasi urutan)
        $latestLog = $logQuery->clone()->latest('id')->first();

        $summaryTemp  = $latestLog->temperature ?? 0;
        $summaryHum   = $latestLog->humidity ?? 0;
        $summaryNoise = $latestLog->noise ?? 0;

        // 🔹 Ambil 10 data terbaru untuk chart (urut dari paling lama ke paling baru)
        $sensors = $logQuery->clone()
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->map(fn($row) => [
                'time'        => \Carbon\Carbon::parse($row->created_at)
                                    ->setTimezone('Asia/Jakarta')
                                    ->format('H:i:s'),
                'temperature' => $row->temperature,
                'humidity'    => $row->humidity,
                'noise'       => $row->noise ?? 0,
            ])
            ->reverse()
            ->values();

        // 🔹 Informasi diagram kereta (status online/offline per car)
        $trainsForDiagram = TrainConfig::with(['cars.devices'])->get()->map(function ($train) {
            $train->cars->each(function ($car) {
                $onlineCount = 0;
                $offlineCount = 0;

                foreach ($car->devices as $device) {
                    $lastLog = MqttLog::where('device_id', $device->serial_number)
                        ->latest('created_at')
                        ->first();

                    if ($lastLog && now()->diffInMinutes($lastLog->created_at) < 5) {
                        $onlineCount++;
                    } else {
                        $offlineCount++;
                    }
                }

                $car->online_devices = $onlineCount;
                $car->offline_devices = $offlineCount;
            });

            return $train;
        });

        // 🔹 Response akhir
        return response()->json([
            'summary' => [
                'temperature'  => $summaryTemp,
                'humidity'     => $summaryHum,
                'noise'        => $summaryNoise,
                'devices'      => $deviceQuery->count(),
                'alerts'       => 0,
                'connectivity' => rand(80, 99),
            ],
            'sensors' => $sensors,
            'diagram' => $trainsForDiagram,
        ]);
    }


    public function downloadDaily(Request $request)
{
    $date = $request->input('date', Carbon::today()->toDateString());
    $logs = MqttLog::whereDate('timestamp', $date)->get();

    $filename = "report-daily-{$date}.csv";
    $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$filename}"];

    $callback = function() use ($logs) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Device', 'Temperature', 'Humidity', 'Noise', 'Timestamp']);
        foreach ($logs as $log) {
            fputcsv($file, [
                $log->device_id,
                $log->temperature,
                $log->humidity,
                $log->noise,
                $log->timestamp,
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function downloadMonthly(Request $request)
{
    $month = $request->input('month', Carbon::now()->format('Y-m'));
    $year = substr($month, 0, 4);
    $monthNum = substr($month, 5, 2);

    $logs = MqttLog::whereYear('timestamp', $year)
                   ->whereMonth('timestamp', $monthNum)
                   ->get();

    $filename = "report-monthly-{$month}.csv";
    $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$filename}"];

    $callback = function() use ($logs) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Device', 'Temperature', 'Humidity', 'Noise', 'Timestamp']);
        foreach ($logs as $log) {
            fputcsv($file, [
                $log->device_id,
                $log->temperature,
                $log->humidity,
                $log->noise,
                $log->timestamp,
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function downloadRange(Request $request)
{
    $from = $request->input('from');
    $to = $request->input('to');

    $logs = MqttLog::whereBetween('timestamp', [$from, $to])->get();

    $filename = "report-range-{$from}_to_{$to}.csv";
    $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$filename}"];

    $callback = function() use ($logs) {
        $file = fopen('php://output', 'w');
        fputcsv($file, ['Device', 'Temperature', 'Humidity', 'Noise', 'Timestamp']);
        foreach ($logs as $log) {
            fputcsv($file, [
                $log->device_id,
                $log->temperature,
                $log->humidity,
                $log->noise,
                $log->timestamp,
            ]);
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
